<?php
namespace ACS\ACSPanelBundle\Tests\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use ACS\ACSPanelBundle\Entity\IpAddress;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadIpAddressData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }

    public function load(ObjectManager $manager)
    {
        // new server
        $ipaddress1 = new IpAddress();
        $ipaddress1->setIp('127.0.0.1');
        $manager->persist($ipaddress1);
        $this->addReference('ipaddress-1', $ipaddress1);

        $manager->flush();
    }
}
