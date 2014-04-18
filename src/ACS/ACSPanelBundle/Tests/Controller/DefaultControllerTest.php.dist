<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        // TODO: Change from fixtures file
        $client = static::createAuthClient('superadmin','1234');

        $crawler = $client->request('GET', '/users');

        //echo($client->getResponse()->getContent());

        $this->assertTrue($crawler->filter('html:contains("User list")')->count() > 0);
    }

}
