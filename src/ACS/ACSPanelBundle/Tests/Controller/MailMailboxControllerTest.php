<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class MailMailboxControllerTest extends CommonTestCase
{
    public function testMailMailboxIndex()
    {
        $client = $this->createSuperadminClient();

        $crawler = $client->request('GET', '/mailmailbox');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());

    }
}
