<?php

namespace ACS\ACSPanelWordpressBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class DatabaseUserControllerTest extends CommonTestCase
{
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $this->client = $this->createSuperadminClient();

        $crawler = $this->client->request('GET', '/wpsetup/new');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Create')->form(array(
            'acs_acspanelwordpressbundle_wpsetuptype[domain][domain]' => 'domain.tld',
            'acs_acspanelwordpressbundle_wpsetuptype[database][name]' => 'domain.tld',
            'acs_acspanelwordpressbundle_wpsetuptype[database][description]' => 'domain.tld',
            'acs_acspanelwordpressbundle_wpsetuptype[user]' => '1',
        ));

        $this->client->submit($form);
    }

}
