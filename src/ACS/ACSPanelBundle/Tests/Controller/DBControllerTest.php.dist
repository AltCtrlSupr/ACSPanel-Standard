<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

class DBControllerTest extends WebTestCase
{
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createAuthClient('superadmin','1234');

        // Create a new entry in the database
        $crawler = $client->request('GET', '/db/');
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());


        // Fill in the form and submit it
        // TODO: Check this to do this test we need a service allowed to create mysql schemas....
        /*$form = $crawler->selectButton('Create')->form(array(
            'acs_acspanelbundle_dbtype[name]'  => 'Test',
            'acs_acspanelbundle_dbtype[user]'  => '1',
            // ... other fields to fill
        ));*/


        //$client->submit($form);


        // Check data in the show view
        //$this->assertTrue($crawler->filter('td:contains("Test")')->count() > 0);

        // Edit the entity
        //$crawler = $client->click($crawler->selectLink('Edit')->link());

        /*$form = $crawler->selectButton('Edit')->form(array(
            'acs_acspanelbundle_dbtype[field_name]'  => 'Foo',
            // ... other fields to fill
        ));*/

        //$client->submit($form);

        // Check the element contains an attribute with value equals "Foo"
        //$this->assertTrue($crawler->filter('[value="Foo"]')->count() > 0);

        // Delete the entity
        //$client->submit($crawler->selectButton('Delete')->form());

        // Check the entity has been delete on the list
        //$this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
        $this->assertTrue(true);
    }

}
