<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class MailWBListControllerTest extends CommonTestCase
{
    public function testMailWBListIndex()
    {
        $client = $this->createSuperadminClient();

        $crawler = $client->request('GET', '/mailwblist');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());

    }
}
