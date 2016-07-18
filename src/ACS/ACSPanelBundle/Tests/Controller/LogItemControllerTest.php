<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class LogItemControllerTest extends CommonTestCase
{
    public function testLogItemIndex()
    {
        $client = $this->createSuperadminClient();

        // Find proper way to handle this
        // $crawler = $client->request('GET', '/logs');
        // $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());
    }
}

