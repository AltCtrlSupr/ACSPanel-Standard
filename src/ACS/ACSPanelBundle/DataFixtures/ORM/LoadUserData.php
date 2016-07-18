<?php
namespace ACS\ACSPanelBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $usermanager = $this->container->get('fos_user.user_manager');

        $userAdmin = $usermanager->createUser();
        $userAdmin->setUsername('superadmin');
        $userAdmin->setEmail('superadmin@admin');
        $userAdmin->setEnabled(true);
        $userAdmin->setPlainPassword('1234');
        $userAdmin->addRole('ROLE_SUPER_ADMIN');

        $usermanager->updateUser($userAdmin);
    }
}


