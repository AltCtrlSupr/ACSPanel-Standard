<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class ServiceControllerTest extends CommonTestCase
{
    public function testServiceIndex()
    {
        $client = $this->createSuperadminClient();

        // Loading form
        $crawler = $client->request('GET', '/service');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/service/new');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
