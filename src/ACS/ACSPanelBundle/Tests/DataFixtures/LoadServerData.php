<?php
namespace ACS\ACSPanelBundle\Tests\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use ACS\ACSPanelBundle\Entity\Server;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadServerData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }

    public function load(ObjectManager $manager)
    {
        // new server
        $server1 = new Server();
        $server1->setHostname('testingserver.tld');
        $server1->setDescription('A testing server');
        $server1->setIp($this->getReference('ipaddress-1'));
        $manager->persist($server1);
        $this->addReference('server-1', $server1);

        $manager->flush();
    }
}
