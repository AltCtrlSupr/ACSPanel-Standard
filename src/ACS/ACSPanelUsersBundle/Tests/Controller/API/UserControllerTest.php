<?php
namespace ACS\ACSPanelUsersBundle\Tests\Controller\API;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use ACS\ACSPanelBundle\Tests\Controller\API\CommonApiTestCase;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class UserControllerTest extends CommonApiTestCase
{
    public function testIndex()
    {
        $client = $this->createSuperadminClient();

        $crawler = $client->request('GET', '/api/users/index.json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJsonResponse($client);
        $this->assertNotRegExp('/password/', $client->getResponse()->getContent());
    }
}
