<?php

namespace ACS\ACSPanelBundle\Tests\Model;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;
use ACS\ACSPanelBundle\Entity\Service;
use ACS\ACSPanelUsersBundle\Entity\User;

class ServiceManagerTest extends CommonTestCase
{
    public function testAttachToUser()
    {
        $service_rep = $this->getContainer()->get('service_manager');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $service = new Service();
        $service->setName('testing service name');

        $user = new User();
        $user->setUsername('serviceallowed');

        $em->persist($service);
        $em->flush();

        $this->assertEquals(false, $service_rep->attachToUser($service, null));
        $this->assertEquals(false, $service_rep->attachToUser(null, $user));
        $this->assertEquals(false, $service_rep->attachToUser(null, null));
        $this->assertEquals(true, $service_rep->attachToUser($service, $user));
    }
}
