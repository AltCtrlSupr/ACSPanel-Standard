<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class FtpdUserControllerTest extends CommonTestCase
{
    public function testFtpdUserIndex()
    {
        $client = $this->createSuperadminClient();

        $crawler = $client->request('GET', '/ftpduser');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());


        $crawler = $client->request('GET', '/ftpduser/new');
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectbutton('Create')->form(array(
            'acs_acspanelbundle_ftpdusertype[userName]' => 'newftpduser',
            'acs_acspanelbundle_ftpdusertype[plain_password]' => '1234',
            'acs_acspanelbundle_ftpdusertype[dir]' => 'new',
            'acs_acspanelbundle_ftpdusertype[quota]' => 1000,
            'acs_acspanelbundle_ftpdusertype[service]' => 1,
            'acs_acspanelbundle_ftpdusertype[user]' => 1,
        ));

        $crawler = $client->submit($form);

        $this->assertequals(200, $this->client->getresponse()->getstatuscode());

        $crawler = $client->request('GET', '/ftpduser/1/edit');
        $this->assertequals(200, $this->client->getresponse()->getstatuscode());

        $form = $crawler->selectbutton('Edit')->form(array(
            'acs_acspanelbundle_ftpdusertype[userName]' => 'newftpduser',
            'acs_acspanelbundle_ftpdusertype[dir]' => 'new',
            'acs_acspanelbundle_ftpdusertype[quota]' => 1000,
            'acs_acspanelbundle_ftpdusertype[service]' => 1,
            'acs_acspanelbundle_ftpdusertype[user]' => 1,
        ));
        $crawler = $client->submit($form);
        $this->assertequals(200, $this->client->getresponse()->getstatuscode());

    }
}
