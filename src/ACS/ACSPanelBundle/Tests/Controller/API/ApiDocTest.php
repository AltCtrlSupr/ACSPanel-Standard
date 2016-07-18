<?php

namespace ACS\ACSPanelBundle\Tests\Controller\API;

class ApiDocTest extends CommonApiTestCase
{
    public function testApiDocAccess()
    {
        $client = $this->createSuperadminClient();
        $crawler = $client->request('GET', '/api/doc');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}

