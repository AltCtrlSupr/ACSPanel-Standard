<?php

namespace ACS\ACSPanelBundle\Tests\Controller\API;

class DnsDomainControllerTest extends CommonApiTestCase
{
    public function testDnsDomainScenario()
    {
        $client = $this->createSuperadminClient();

        // DNS Domain index
        $crawler = $client->request('GET', '/api/dnsdomains/index.json');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // Check if the respense contents are json
        $this->assertJsonResponse($client);

        // DNS Domain show
        $crawler = $client->request('GET', '/api/dnsdomains/1/show.json');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // Check if the respense contents are json
        $this->assertJsonResponse($client);
        $this->assertRegExp('/{"id":1,"domain":"0domain.tld"/', $client->getResponse()->getContent());

        // DNS Domain create with body
        $crawler = $client->request('POST', '/api/dnsdomains/create.json', array(
            'dnsdomaintype' => array(
                'domain' => 15,
                'type' => 'master',
            )
        ));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        // Check if the respense contents are json
        $this->assertJsonResponse($client);

        // DNS Domain update
        $crawler = $client->request('PATCH', '/api/dnsdomains/2/update.json', array(
            'dnsdomaintype' => array(
                'domain' => 14,
                'type' => 'SLAVE'
            )
        ));
        $this->assertJsonResponse($client);

        $crawler = $client->request('DELETE', '/api/dnsdomains/2.json');
        $this->assertJsonResponse($client);
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
}

