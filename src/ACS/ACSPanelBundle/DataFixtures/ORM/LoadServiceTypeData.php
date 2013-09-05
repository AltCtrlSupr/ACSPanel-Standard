<?php
namespace ACS\ACSPanelBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use ACS\ACSPanelBundle\Entity\FosUser;
use ACS\ACSPanelBundle\Entity\ServiceType;

class LoadServiceTypeData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $db_type = new ServiceType();
        $db_type->setName('DB');

        $manager->persist($db_type);
        $manager->flush();
    }
}


