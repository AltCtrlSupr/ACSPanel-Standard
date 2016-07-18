<?php

namespace ACS\ACSPanelBundle\Tests\Controller\API;

class DynamicDnsControllerTest extends CommonApiTestCase
{
    public function testUpdateDns()
    {
        $client = $this->createSuperadminClient();

        // DynDNS like call
        // http://username:password@members.dyndns.org/nic/update?hostname=yourhostname&myip=ipaddress
        $crawler = $client->request('GET', '/nic/update?hostname=1domain.tld&myip=8.8.8.8');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // Check if the respense contents are json
        $this->assertJsonResponse($client);

        $crawler = $client->request('GET', '/nic/update?hostname=1domain.tld');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // Check if the respense contents are json
        $this->assertJsonResponse($client);

        // Check if I can update ndomain not owned by me
        $crawler = $client->request('GET', '/nic/update?hostname=notmine.tld');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());

        // Not really API but related with dynamic dns
        $crawler = $client->request('GET', '/dyndns/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Create')->form(array(
            'acs_acspanelbundle_dnsrecordtype[subdomain]' => 'test',
            'acs_acspanelbundle_dnsrecordtype[dns_domain]' => 1,
            'acs_acspanelbundle_dnsrecordtype[content]' => '6.6.6.6',
        ));

        $crawler = $client->submit($form);

        // Should we receive 200 code
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
    }
}
