<?php
namespace ACS\ACSPanelBundle\Tests\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use ACS\ACSPanelBundle\Entity\Domain;

class LoadDomainData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // Adding 15 domains for superadmin
        for ($i=0; $i < 15; $i++) {
            $domain = new Domain();
            $domain->setDomain($i . 'domain.tld');
            $domain->setEnabled(true);
            $domain->setUser($this->getReference('user-super-admin'));

            $manager->persist($domain);
            $this->addReference('domain-' . $i, $domain);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
