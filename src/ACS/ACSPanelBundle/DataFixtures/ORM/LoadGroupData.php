<?php
namespace ACS\ACSPanelBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use ACS\ACSPanelUsersBundle\Entity\FosGroup;

class LoadGroupData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $adm_group = new FosGroup();
        $adm_group->setName('Administrators');
        $adm_group->addRole('ROLE_ADMIN');
        $manager->persist($adm_group);

        $rs_group = new FosGroup();
        $rs_group->setName('Resellers');
        $rs_group->addRole('ROLE_RESELLER');
        $manager->persist($rs_group);

        $usr_group = new FosGroup();
        $usr_group->setName('Users');
        $usr_group->addRole('ROLE_USER');
        $manager->persist($usr_group);

        $manager->flush();
    }
}


