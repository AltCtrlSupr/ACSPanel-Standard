<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

class DefaultControllerTest extends CommonTestCase
{
    public function testIndex()
    {

        $crawler = $this->requestWithAuth('superadmin',$this->client, 'GET', '/users');

        //echo($client->getResponse()->getContent());

        $this->assertTrue($crawler->filter('html:contains("User list")')->count() > 0);
    }

}
