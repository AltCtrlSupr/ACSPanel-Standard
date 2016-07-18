<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class DomainControllerTest extends CommonTestCase
{
    public function testDomainScenario()
    {
        $client = $this->createSuperadminClient();

        $crawler = $client->request('GET', '/domain');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/domain/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // form should accept empty protected dir
        $form = $crawler->selectbutton('Create')->form(array(
            'acs_acspanelbundle_domaintype[domain]' => 'test.com',
            'acs_acspanelbundle_domaintype[add_dns_domain]' => true,
        ));

        $crawler = $client->submit($form);
        $this->assertequals(200, $client->getresponse()->getstatuscode());
    }
}

