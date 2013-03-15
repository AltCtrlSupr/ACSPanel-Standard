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

    public function getUserSetting($setting_key, FosUser $user)
    {
        $setting = $this->findOneBy(array(
            'user' => $user,
            'setting_key' => $setting_key,
        ));
        if($setting)
            return $setting->getValue();

        return null;

    }
}
