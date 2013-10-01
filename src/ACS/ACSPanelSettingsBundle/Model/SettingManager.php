<?php
namespace ACS\ACSPanelSettingsBundle\Model;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

use ACS\ACSPanelBundle\Entity\FosUser;

abstract class SettingManager extends EntityRepository
{
    private $container;

    public function __construct($em, $class, $container)
    {
        $this->container = $container;
        $class_object = new ClassMetadata($class);
        $this->container = $container;

        parent::__construct($em, $class_object);
    }

    /**
     * Returns true if there is a update of the user fields
     */
    public function isUpdateAvailable($user, $version_key, $schema_version)
    {
        $user_fields_version = $this->getSetting($version_key, 'user_internal', $user);
        if(!$user_fields_version) $user_fields_version = 0;
        if($user_fields_version < $schema_version)
            return true;

        return false;
    }

    /**
     * Loading of settings needed for the app
     * Called if it's the first time or user need update of settings
     */
    public function loadSettingDefaults(array $fields, $user)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        foreach($fields as $field){
            if(!$this->getSetting($field['setting_key'], $field['focus'], $user)){
                $this->setSetting($field['setting_key'], $field['focus'], $field['default_value'], $field['context']);
            }
        }

        //TODO: Increment schema_version setting
    }

    /**
     * TODO: Prepare to get prototype for any tipe of object
     */
    public function getObjectSettingsPrototype($user, $object_class = 'ACSACSPanelBundle:Service')
    {
        // TODO: Transform object_fields to config like array
        // Get the object fields
        $em = $this->getEntityManager();

        $settings_objects = $em->getRepository($object_class)->findBy(array(
            'user' => $user
        ));

        $object_settings = array();
        foreach ($settings_objects as $setting_obj){
            $object_fields = $setting_obj->getType()->getFieldTypes();
            foreach($object_fields as $field){
                // TODO: Create filter to do this.. :)
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
     * TODO: Call from controller
     */
    public function loadObjectSettingsDefaults($object_id)
    {
        $em = $this->getEntityManager();

        $object = $em->getRepository('ACSACSPanelBundle:Service')->find($object_id);
        $object_fields = $object->getType()->getFieldTypes();

        $user = $this->get('security.context')->getToken()->getUser();

    }

    /**
     * Get setting by parameters
     */
    public function getSetting($setting_key, $focus, $user = null)
    {
        $params = array();
        $params['setting_key'] = $setting_key;
        $params['focus'] = $focus;
        if($user)
            $params['user'] = $user;

        $setting = $this->findOneBy($params);

        if($setting)
            return $setting->getValue();

        return null;
    }

    /**
     * Sets a setting value
     */
    public function setSetting($setting_key, $focus, $value, $context = '')
    {
        $em = $this->getEntityManager();

        $setting = $this->findOneBy(array(
            'setting_key' => $setting_key,
            'focus' => $focus,
        ));

        // We create the new setting if it not exists
        if(!$setting){
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
     * @todo: Maybe could be good caching those values...
     */
    public function getUserSetting($setting_key, FosUser $user)
    {
        $setting = $this->getSetting($setting_key, 'user_setting', $user);
        return $setting;
    }

    /**
     * Gets system focus config values from database
     * @todo: Maybe could be good caching those values...
     */
    public function getSystemSetting($setting_key)
    {
        $setting = $this->getSetting($setting_key, 'system_setting');
        return $setting;
    }

    /**
     * Gets internal focus config values from database
     * @todo: Maybe could be good caching those values...
     */
    public function getInternalSetting($setting_key)
    {
        $setting = $this->getSetting($setting_key, 'internal');
        return $setting;
    }
}
