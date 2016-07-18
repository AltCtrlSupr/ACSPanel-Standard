<?php

namespace ACS\ACSPanelUsersBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use ACS\ACSPanelUsersBundle\Entity\User;

class UserRepositoryFunctionalTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
    }

    public function testGetAdminUsers()
    {
        $admin = new User();
        $admin->addRole('ROLE_ADMIN');
        $admin->setUsername('test_username');
        $admin->setEmail('test_username@test');
        $admin->setPassword('1234');
        $this->em->persist($admin);
        $this->em->flush();

        $admins = $this->em->getRepository('ACSACSPanelUsersBundle:User')
            ->findByUsername('test_username')
        ;
        $this->assertCount(1, $admins);

        $admins = $this->em->getRepository('ACSACSPanelUsersBundle:User')
            ->getAdminUsers()
        ;

        $this->assertCount(2, $admins);
    }
}

