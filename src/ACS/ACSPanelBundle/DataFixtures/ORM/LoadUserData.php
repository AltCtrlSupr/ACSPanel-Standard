<?php
namespace ACS\ACSPanelBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use ACS\ACSPanelBundle\Entity\FosUser;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }

        $usermanager = $kernel->getContainer()->get('fos_user.user_manager');

        $userAdmin = $usermanager->createUser();
        $userAdmin->setUsername('superadmin');
        $userAdmin->setEmail('superadmin@admin');
        $userAdmin->setEnabled(true);
        $userAdmin->setPlainPassword('1234');
        $userAdmin->addRole('ROLE_SUPER_ADMIN');

        $usermanager->updateUser($userAdmin);
    }
}


