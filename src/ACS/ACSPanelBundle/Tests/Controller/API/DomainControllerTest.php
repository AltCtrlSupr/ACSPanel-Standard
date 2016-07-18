<?php

namespace ACS\ACSPanelBundle\Tests\Controller\API;

class DomainControllerTest extends CommonApiTestCase
{
    public function testDomainScenario()
    {
        $client = $this->createSuperadminClient();

        $crawler = $this->client->request('GET', '/api/domains/index.json');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Check if the respense contents are json
        $this->assertJsonResponse($client);

        $this->assertRegExp('/{"id":1,"domain":"0domain.tld"/', $client->getResponse()->getContent());

        $this->assertNotRegExp('/password/', $client->getResponse()->getContent());

        // Show one domain
        $crawler = $this->client->request('GET', '/api/domains/1/show.json');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Check if the respense contents are json
        $this->assertJsonResponse($client);

        // Creating new domains with body
        $crawler = $this->client->request('POST', '/api/domains/create.json', array(
            'acs_acspanelbundle_domaintype' => array('domain' => 'test.cat')
        ));

        // Check if the respense contents are json
        $this->assertJsonResponse($client);

        $crawler = $this->client->request('GET', '/api/domains/2domain/search.json');
        // Check if the respense contents are json
        $this->assertJsonResponse($client);
        $this->assertRegExp('/{"id":3,"domain":"2domain.tld","created_at":"/', $client->getResponse()->getContent());

        // Modifying domains with body
        $crawler = $this->client->request('PATCH', '/api/domains/2/update.json', array(
            'acs_acspanelbundle_domaintype' => array('domain' => 'test.cat')
        ));
        $this->assertJsonResponse($client);
        $this->assertRegExp('/{"id":2,"domain":"test.cat","created_at":"/', $client->getResponse()->getContent());

        $crawler = $this->client->request('DELETE', '/api/domains/2.json');
        $this->assertJsonResponse($client);
    }
}
