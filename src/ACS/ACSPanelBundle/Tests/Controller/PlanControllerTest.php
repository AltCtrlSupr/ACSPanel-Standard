<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class PlanControllerTest extends CommonTestCase
{
    public function testPlanIndex()
    {
        $client = $this->createSuperadminClient();

        // Loading form
        $crawler = $client->request('GET', '/plans');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());

    }
}
