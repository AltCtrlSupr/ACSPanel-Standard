<?php

namespace ACS\ACSPanelBundle\Tests\Controller\API;

class MailAliasControllerTest extends CommonApiTestCase
{
    public function testServiceScenario()
    {
        $client = $this->createSuperadminClient();

        $crawler = $this->client->request('GET', '/api/mailaliases/index.json');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Check if the respense contents are json
        $this->assertJsonResponse($client);
    }
}

