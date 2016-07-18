<?php

namespace ACS\ACSPanelBundle\Tests\Controller\API;

class HttpdUserControllerTest extends CommonApiTestCase
{
    public function testHttpdUsersScenario()
    {
        $client = $this->createSuperadminClient();

        $crawler = $this->client->request('GET', '/api/httpdusers/index.json');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Check if the respense contents are json
        $this->assertJsonResponse($client);
    }
}

