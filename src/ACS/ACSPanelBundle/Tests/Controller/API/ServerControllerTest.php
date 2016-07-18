<?php

namespace ACS\ACSPanelBundle\Tests\Controller\API;

class ServerControllerTest extends CommonApiTestCase
{
    public function testServerScenario()
    {
        $client = $this->createSuperadminClient();

        $crawler = $this->client->request('GET', '/api/servers/index.json');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Check if the respense contents are json
        $this->assertJsonResponse($client);
    }
}

