<?php
namespace ACS\ACSPanelSettingsBundle\Model;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

abstract class SettingManager extends EntityRepository
{
    public function __construct($em, $class)
    {
        $class_object = new ClassMetadata($class);
        parent::__construct($em, $class_object);
    }
}
