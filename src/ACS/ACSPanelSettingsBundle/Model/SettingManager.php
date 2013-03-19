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
     * Gets user focus config value from database
     * @todo: Maybe could be good caching those values...
     */
    public function getUserSetting($setting_key, FosUser $user)
    {
        $setting = $this->findOneBy(array(
            'user' => $user,
            'setting_key' => $setting_key,
            'focus' => 'user_setting',
        ));
        if($setting)
            return $setting->getValue();

        return null;

    }

    /**
     * Gets system focus config values from database
     * @todo: Maybe could be good caching those values...
     */
    public function getSystemSetting($setting_key)
    {
        $setting = $this->findOneBy(array(
            'setting_key' => $setting_key,
            'focus' => 'system_setting',
        ));
        if($setting)
            return $setting->getValue();

        return null;

    }

}
