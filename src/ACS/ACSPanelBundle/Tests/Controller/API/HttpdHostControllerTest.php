<?php

namespace ACS\ACSPanelBundle\Tests\Controller\API;

class HttpdHostControllerTest extends CommonApiTestCase
{
    public function testHttpdHostScenario()
    {
        $client = $this->createSuperadminClient();

        $crawler = $this->client->request('GET', '/api/httpdhosts/index.json');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Check if the respense contents are json
        $this->assertJsonResponse($client);
    }
}

