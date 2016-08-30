<?php

namespace ACS\ACSPanelBundle\Tests\Controller\API;

class DnsRecordControllerTest extends CommonApiTestCase
{
    public function testDnsDomainScenario()
    {
        $client = $this->createSuperadminClient();

        $record = [
            'dnsrecordtype' => array(
                'name' => 'loremipsum',
                'type' => 'CNAME',
                'content' => '8.8.8.8',
                'ttl' => 20,
                'prio' => 20,
                'dns_domain' => 1,
            )
        ];

        // DNS Record create with body
        $client->request('POST', '/api/dnsrecords/create.json', $record);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        // Check if the respense contents are json
        $this->assertJsonResponse($client);
    }
}
