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

    /**
     * Sets a setting value
     */
    public function setSetting($setting_key, $focus, $value)
    {
        $em = $this->getEntityManager();

        $setting = $this->findOneBy(array(
            'setting_key' => $setting_key,
            'focus' => $focus,
        ));

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
        return $this->setSetting($setting_key, 'internal', $value);
    }
}
