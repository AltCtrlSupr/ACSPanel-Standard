<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class HttpdHostControllerTest extends CommonTestCase
{
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $this->client = $this->createAuthorizedClient('superadmin','1234');

        // Create a new entry in the database
        $crawler = $this->client->request('GET', '/httpdhost/');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/httpdhost/new');
        $this->assertTrue($crawler->filter('.form_box')->count() > 0);

        $crawler = $this->client->request('GET', '/httpdhost/new');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());

        // Form to create new httpdhost
        $form = $crawler->selectButton('Create')->form(array(
            'acs_acspanelbundle_httpdhosttype[domain]' => 1,
            // 'acs_acspanelbundle_httpdhosttype[service]' => 1,
        ));
        $form['acs_acspanelbundle_httpdhosttype[php]']->tick();
        $form['acs_acspanelbundle_httpdhosttype[add_www_alias]']->tick();
        $crawler = $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

	}
}
