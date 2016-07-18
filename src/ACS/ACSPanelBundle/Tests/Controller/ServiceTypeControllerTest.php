<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class ServiceTypeControllerTest extends CommonTestCase
{
    public function testServiceIndex()
    {
        $client = $this->createSuperadminClient();

        $crawler = $client->request('GET', '/servicetype');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());

    }
}
