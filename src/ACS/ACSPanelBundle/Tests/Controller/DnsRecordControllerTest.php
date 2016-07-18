<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class DnsRecordControllerTest extends CommonTestCase
{
    public function testDnsDomainScenario()
    {
        $client = $this->createSuperadminClient();

        $crawler = $this->client->request('GET', '/dnsrecord/new');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Form should accept empty protected dir
        $form = $crawler->selectButton('Create')->form(array(
            'dnsrecordtype[dns_domain]' => 2,
        ));
        $crawler = $client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
