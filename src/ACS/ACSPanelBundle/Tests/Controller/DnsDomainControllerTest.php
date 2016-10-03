<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class DnsDomainControllerTest extends CommonTestCase
{
    public function testDnsDomainScenario()
    {
        $client = $this->createSuperadminClient();

        $crawler = $client->request('GET', '/dnsdomain');
        dump($client->getResponse()->getContent()); die;
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/dnsdomain/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
