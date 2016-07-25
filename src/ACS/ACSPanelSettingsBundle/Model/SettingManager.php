<?php

namespace ACS\ACSPanelSettingsBundle\Model;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use ACS\ACSPanelSettingsBundle\Event\SettingsEvents;
use ACS\ACSPanelSettingsBundle\Event\FilterUserFieldsEvent;

abstract class SettingManager extends EntityRepository
{
    private $container;

    public function __construct($em, $class, $container)
    {
        $this->container = $container;
        $class_object = new ClassMetadata($class);

        parent::__construct($em, $class_object);
    }

    /**
     * Returns true if there is a update of the user fields
     */
    public function isUpdateAvailable($user, $version_key, $schema_version)
    {
        $user_fields_version = $this->getSetting($version_key, 'user_internal', $user);
        if (!$user_fields_version) {
            $user_fields_version = 0;
        }

        if ($user_fields_version < $schema_version) {
            return true;
        }

        return false;
    }

    /**
     * Loading of settings needed for the app
     * Called if it's the first time or user need update of settings
     */
    public function loadSettingDefaults(array $fields, $user)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        foreach($fields as $field){
            if (!$this->getSetting($field['setting_key'], $field['focus'], $user)) {
                if(!isset($field['default_value'])) {
                    $field['default_value'] = '';
                }
                $this->setSetting($field['setting_key'], $field['focus'], $field['default_value'], $field['context'], $user);
            }
        }

        //TODO: Increment schema_version setting
    }

    /**
     */
    public function getObjectSettingsPrototype($id, $object_class = 'ACSACSPanelBundle:Service')
    {
        // Get the object fields
        $em = $this->getEntityManager();

        $settings_objects = $em->getRepository($object_class)->findById($id);

        $object_settings = array();
        foreach ($settings_objects as $setting_obj){
            $object_fields = $setting_obj->getType()->getFieldTypes();
            foreach($object_fields as $field){
                $object_settings[] = array(
                    'setting_key' => $field->getSettingKey(),
                    'label' => $field->getLabel(),
                    'field_type' => $field->getType(),
                    'default_value' => $field->getDefaultValue(),
                    'context' => $field->getContext(),
                    'focus' => 'object_setting',
                    'service_id' => $setting_obj->getId(),
                );
            }
        }

        return $object_settings;
    }

    /**
     * Create the settings configured for specified object
     */
    public function loadObjectSettingsDefaults($object_id)
    {
        $em = $this->getEntityManager();

        $object = $em->getRepository('ACSACSPanelBundle:Service')->find($object_id);
        $object_fields = $object->getType()->getFieldTypes();

        return $object_fields;
    }

    /**
     * Get setting by parameters
     */
    public function getSetting($setting_key, $focus = null, $user = null)
    {
        $params = array();
        $params['setting_key'] = $setting_key;

        if ($focus) {
            $params['focus'] = $focus;
        }

        if ($user) {
            $params['user'] = $user;
        }

        $setting = $this->findOneBy($params);

        if ($setting) {
            return $setting->getValue();
        }

        return null;
    }

    /**
     * Sets a setting value
     */
    public function setSetting($setting_key, $focus, $value, $context = '', $user = null)
    {
        $em = $this->getEntityManager();

        $params = array();
        $params['setting_key'] = $setting_key;
        $params['focus'] = $focus;

        if ($context) {
            $params['context'] = $context;
        }
        if ($user) {
            $params['user'] = $user;
        }

        $setting = $this->findOneBy($params);

        // We create the new setting if it not exists
        if (!$setting) {
            $class_name = $this->container->getParameter('acs_settings.setting_class');
            $setting = new $class_name;
            $setting->setSettingKey($setting_key);
            $setting->setFocus($focus);
            $setting->setContext($context);
        }

        $setting->setValue($value);

        $em->persist($setting);
        $em->flush();

        return $setting;
    }

    /**
     * Sets internal setting value
     */
    public function setInternalSetting($setting_key, $value)
    {
        return $this->setSetting($setting_key, 'internal', $value, 'internal');
    }

    /**
     * Gets user focus config value from database
     */
    public function getUserSetting($setting_key, $user)
    {
        $setting = $this->getSetting($setting_key, 'user_setting', $user);
        return $setting;
    }

    /**
     * Gets system focus config values from database
     */
    public function getSystemSetting($setting_key)
    {
        $setting = $this->getSetting($setting_key, 'system_setting');
        return $setting;
    }

    public function getSystemSettings()
    {
        $settings = $this->findBy(array('focus' => 'system_setting'));
        return $settings;
    }

    /**
     * Gets internal focus config values from database
     */
    public function getInternalSetting($setting_key)
    {
        $setting = $this->getSetting($setting_key, 'internal');
        return $setting;
    }

    /**
     * Returns the context used to organize the settings view
     */
    public function getContexts($user)
    {
        $em = $this->getEntityManager();

        $contexts_rep = $em->getRepository('ACSACSPanelBundle:PanelSetting');
        $query = $contexts_rep->createQueryBuilder('ps')
            ->select('ps.context')
            ->where('ps.user = ?1')
            ->andWhere('ps.context NOT LIKE ?2')
            ->andWhere('ps.context NOT LIKE ?3')
            ->andWhere('ps.context NOT LIKE ?4')
            ->groupBy('ps.context')
            ->orderBy('ps.context')
            ->setParameter('1', $user)
            ->setParameter('2', 'internal')
            ->setParameter('3', 'user_internal')
            ->setParameter('4', 'system_internal')
            ->getQuery()
        ;

        $contexts = $query->execute();

        return $contexts;
    }

    /**
     * Load the settings array to pass to form
     */
    function loadUserFields()
    {
        $user_fields = array();

        $this->container->get('event_dispatcher')->dispatch(SettingsEvents::BEFORE_LOAD_USERFIELDS, new FilterUserFieldsEvent($user_fields, $this->container));

        array_merge($user_fields, $user_fields = $this->container->getParameter("acs_settings.user_fields"));

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        // If is admins we load the global system settings
        if (true === $this->container->get('security.token_storage')->isGranted('ROLE_SUPER_ADMIN')) {
            $user_fields = array_merge($user_fields, $system_fields = $this->container->getParameter("acs_settings.system_fields"));
        }

        $object_settings = $this->container->get('acs.setting_manager')->getObjectSettingsPrototype($user);

        $user_fields = array_merge($user_fields, $object_settings);

        $this->container->get('event_dispatcher')->dispatch(SettingsEvents::AFTER_LOAD_USERFIELDS, new FilterUserFieldsEvent($user_fields,$this->container));

        return $user_fields;
    }
}
