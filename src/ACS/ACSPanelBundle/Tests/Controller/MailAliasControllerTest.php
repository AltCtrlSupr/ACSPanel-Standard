<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class MailAliasControllerTest extends CommonTestCase
{
    public function testMailAliasIndex()
    {
        $client = $this->createSuperadminClient();

        $crawler = $client->request('GET', '/mailalias');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/mailalias/new');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());
    }
}
