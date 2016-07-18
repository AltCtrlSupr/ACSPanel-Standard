<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class MailLogRcvdControllerTest extends CommonTestCase
{
    public function testMailRcvdIndex()
    {
        $client = $this->createSuperadminClient();

        $crawler = $client->request('GET', '/maillogrcvd');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());

    }
}
