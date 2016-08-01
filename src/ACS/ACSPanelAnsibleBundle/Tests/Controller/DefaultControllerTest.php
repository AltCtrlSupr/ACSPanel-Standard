<?php

namespace ACS\ACSPanelAnsibleBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class DefaultControllerTest extends CommonTestCase
{
    public function testIndex()
    {
        $client = $this->createSuperadminClient();

        $crawler = $client->request('GET', '/ansible/inventory');

        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode()
        );

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }
}
