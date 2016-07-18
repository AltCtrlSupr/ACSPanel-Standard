<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class MailDomainControllerTest extends CommonTestCase
{
    public function testMailDomainIndex()
    {
        $client = $this->createSuperadminClient();

        $crawler = $client->request('GET', '/maildomain');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/maildomain/new');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());


        $form = $crawler->selectButton('Create')->form(array(
            'acs_acspanelbundle_maildomaintype[domain]' => 1,
            'acs_acspanelbundle_maildomaintype[description]' => "Lorem Ipsum Amet",
            'acs_acspanelbundle_maildomaintype[maxAliases]' => 10,
            'acs_acspanelbundle_maildomaintype[maxMailboxes]' => 10,
            'acs_acspanelbundle_maildomaintype[maxQuota]' => 10,
            'acs_acspanelbundle_maildomaintype[backupmx]' => false,
        ));

        $crawler = $client->submit($form);

        // Should we receive 200 code
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
    }
}
