<?php
namespace ACS\ACSPanelSettingsBundle\Model;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

use ACS\ACSPanelBundle\Entity\FosUser;

abstract class SettingManager extends EntityRepository
{
    public function __construct($em, $class)
    {
        $class_object = new ClassMetadata($class);
        parent::__construct($em, $class_object);
    }

    /**
     * Loading of basic internal settings needed for the app
     * Called if it's the first time or user need update of settings
     */
    public function loadFileSettingDefaults(array $fields)
    {
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        $user = $kernel->getContainer()->get('security.context')->getToken()->getUser();
        foreach($fields as $field){
            if(!$this->getSetting($field['setting_key'], $field['focus'], $user)){
                $this->setSetting($field['setting_key'], $field['focus'], $field['value'], $field['context']);
            }
        }

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
    public function loadObjectSettingsDefaults()
    {
        $em = $this->getEntityManager();

        $class_name = $this->container->getParameter('acs_settings.setting_class');

        // Get the object fields
        // TODO: Decouple this
        $object = $em->getRepository('ACSACSPanelBundle:Service')->find($object_id);
        $object_fields = $object->getType()->getFieldTypes();

        // TODO: Check in this point if user has rights to access to that service settings
        // if (true === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // $system_fields = $this->container->getParameter("acs_settings.system_fields");
            // $user_fields = array_merge($user_fields, $system_fields);
        // }


        $user = $this->get('security.context')->getToken()->getUser();

        // $form_collection = new ConfigSettingCollectionType($user_fields);
        // Adding one form for each setting field
        foreach($object_fields as $id => $field_config){
            // TODO: To get from config.yml
            $setting = $em->getRepository('ACSACSPanelBundle:PanelSetting')->findOneBy(
                array(
                    'user' => $user->getId(),
                    'setting_key' => $field_config->getSettingKey(),
                    'focus' => 'object_setting',
                    // TODO uncouple this
                    'service' => $object,
                ));
            if(!count($setting)){
                $setting = new $class_name;
                $setting->setSettingKey($field_config->getSettingKey());
                $setting->setValue($field_config->getDefaultValue());
                $setting->setContext($field_config->getContext());
                $setting->setLabel($field_config->getLabel());
                $setting->setType($field_config->getType());
                // TODO: implement choices
                // if(isset($field_config['choices']))
                    // $setting->setChoices($field_config['choices']);
                $setting->setFocus('object_setting');
                // TODO: Uncouple this
                $setting->setService($object);
                $user->addSetting($setting);
                $em->persist($user);
                $em->flush();
            }
        }


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
            global $kernel;

            if ('AppCache' == get_class($kernel)) {
                $kernel = $kernel->getKernel();
            }
            $class_name = $kernel->getContainer()->getParameter('acs_settings.setting_class');
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
