<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class IpAddressControllerTest extends CommonTestCase
{
    public function testIpAddressIndex()
    {
        $client = $this->createSuperadminClient();

        $crawler = $client->request('GET', '/ipaddress');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());

    }
}
