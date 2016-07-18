<?php
namespace ACS\ACSPanelBundle\Tests\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use ACS\ACSPanelBundle\Entity\DnsDomain;
use ACS\ACSPanelBundle\Entity\DnsRecord;

class LoadDnsDomainData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // Adding 15 dns-domains for superadmin
        for ($i=0; $i < 10; $i++) {
            $ddomain = new DnsDomain();
            $ddomain->setType('MASTER');
            $ddomain->setEnabled(true);
            $ddomain->setDomain($this->getReference('domain-'. $i));
            $ddomain->setPublic(true);

            $drecord = new DnsRecord();
            $drecord->setName($ddomain->__toString());
            $drecord->setContent('127.0.0.1');
            $drecord->setTtl('1');
            $drecord->setType('A');

            $drecord->setDnsDomain($ddomain);

            $manager->persist($drecord);
            $manager->persist($ddomain);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}
