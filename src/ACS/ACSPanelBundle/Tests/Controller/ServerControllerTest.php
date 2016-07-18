<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class serverControllerTest extends CommonTestCase
{
    public function testServerIndex()
    {
        $client = $this->createSuperadminClient();

        // Loading form
        $crawler = $client->request('GET', '/server');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/server/1/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
