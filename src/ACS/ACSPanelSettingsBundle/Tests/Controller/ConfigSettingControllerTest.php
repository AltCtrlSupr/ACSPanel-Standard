<?php

namespace ACS\ACSPanelSettingsBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class ConfigSettingControllerTest extends CommonTestCase
{
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = $this->createAuthorizedClient('superadmin','1234');

        // Create a new entry in the database
        $crawler = $client->request('GET', '/settings/');
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());

        // Form should accept empty protected dir
        $form = $crawler->selectButton('Save')->form(array(
            'acs_settings_usersettings[settings][10][value]' => 'dns.acs.li',
            'acs_settings_usersettings[settings][4][value]' => 'testing',
            'acs_settings_usersettings[settings][5][value]' => 'testing'
        ));

        $crawler = $client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Test createObjectSettingsAction
        $crawler = $client->request('GET', '/settings/1/settings');
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
    }
}
