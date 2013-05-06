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
