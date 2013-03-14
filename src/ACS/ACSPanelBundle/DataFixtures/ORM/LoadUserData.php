<?php
namespace ACS\ACSPanelBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use ACS\ACSPanelBundle\Entity\FosUser;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userAdmin = new FosUser();
        $userAdmin->setUsername('admin');
        $userAdmin->setPassword('1234');

        $manager->persist($userAdmin);
        $manager->flush();
    }
}


