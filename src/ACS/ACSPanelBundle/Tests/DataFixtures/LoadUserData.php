<?php
namespace ACS\ACSPanelBundle\Tests\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use ACS\ACSPanelBundle\Entity\FosUser;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $usermanager = $this->container->get('fos_user.user_manager');

        $userSuperAdmin = $usermanager->createUser();
        $userSuperAdmin->setUsername('superadmin');
        $userSuperAdmin->setEmail('superadmin@admin');
        $userSuperAdmin->setEnabled(true);
        $userSuperAdmin->setPlainPassword('1234');
        $userSuperAdmin->addRole('ROLE_SUPER_ADMIN');

        $usermanager->updateUser($userSuperAdmin);

        $this->addReference('user-super-admin',$userSuperAdmin);

        $userAdmin = $usermanager->createUser();
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('instructor@admin');
        $userAdmin->setEnabled(true);
        $userAdmin->setPlainPassword('1234');
        $userAdmin->addRole('ROLE_ADMIN');

        $usermanager->updateUser($userAdmin);

        $this->addReference('user-admin',$userAdmin);

        $userReseller = $usermanager->createUser();
        $userReseller->setUsername('center1');
        $userReseller->setEmail('center1@admin');
        $userReseller->setEnabled(true);
        $userReseller->setPlainPassword('1234');
        $userReseller->addRole('ROLE_RESELLER');

        $usermanager->updateUser($userReseller);

        $this->addReference('user-reseller',$userReseller);


        $user = $usermanager->createUser();
        $user->setUsername('center2');
        $user->setEmail('center2@admin');
        $user->setEnabled(true);
        $user->setPlainPassword('1234');
        $user->addRole('ROLE_CENTER');

        $usermanager->updateUser($user);

        $this->addReference('user',$user);

    }

    public function getOrder()
    {
        return 1;
    }
}


