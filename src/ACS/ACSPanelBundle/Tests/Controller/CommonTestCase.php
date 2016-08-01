<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

abstract class CommonTestCase extends WebTestCase
{
    /**
     * Fixtures to load
     */
    private $fixtures = [
        'ACS\ACSPanelBundle\Tests\DataFixtures\LoadUserData',
        'ACS\ACSPanelBundle\Tests\DataFixtures\LoadDomainData',
        'ACS\ACSPanelBundle\Tests\DataFixtures\LoadDnsDomainData',
        'ACS\ACSPanelBundle\Tests\DataFixtures\LoadPlanData',
        'ACS\ACSPanelBundle\Tests\DataFixtures\LoadServiceTypeData',
        'ACS\ACSPanelBundle\Tests\DataFixtures\LoadIpAddressData',
        'ACS\ACSPanelBundle\Tests\DataFixtures\LoadServerData',
        'ACS\ACSPanelBundle\Tests\DataFixtures\LoadServiceData',
        'ACS\ACSPanelBundle\Tests\DataFixtures\LoadDBData',
    ];

    private $em;

    public $client;

    public function setUp()
    {
        $this->loadFixtures($this->fixtures);

        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $this->createSuperadminClient('superadmin');
    }

    protected function createAuthorizedClient($username)
    {
        $admin_user = $this->em->getRepository('ACSACSPanelUsersBundle:User')->findOneByUsername($username);

        $this->loginAs($admin_user, 'main');

        $this->client = $this->makeClient(true);

        $this->client->followRedirects();

        return $this->client;
    }

    public function createSuperadminClient()
    {
        $this->client = $this->createAuthorizedClient('superadmin');
        return $this->client;
    }
}
