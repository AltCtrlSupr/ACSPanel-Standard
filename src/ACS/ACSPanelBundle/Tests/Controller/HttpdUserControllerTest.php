<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class HttpdUserControllerTest extends CommonTestCase
{
    public function testCompleteScenario()
    {
        $client = $this->createSuperadminClient();

        $crawler = $client->request('GET', '/httpduser');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());

        // Loading form
        $crawler = $client->request('GET', '/httpduser/new');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());

        // Form should accept empty protected dir
        $form = $crawler->selectButton('Create')->form(array(
            'acs_acspanelbundle_httpdusertype[name]' => 'httpd_test',
            'acs_acspanelbundle_httpdusertype[password]' => '1234',
            'acs_acspanelbundle_httpdusertype[protected_dir]' => '',
        ));

        $crawler = $client->submit($form);

        $this->assertRegExp('/This value should not be blank/', $client->getResponse()->getContent());

        // Should we receive 200 code
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());
}

}
