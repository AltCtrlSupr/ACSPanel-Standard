<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createAuthClient('superadmin','1234');

        $crawler = $client->request('GET', '/');

        echo($client->getResponse()->getContent());

        $this->assertTrue($crawler->filter('html:contains("Quota List")')->count() > 0);
    }

}
