<?php
namespace ACS\ACSPanelBundle\Tests\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use ACS\ACSPanelBundle\Entity\Domain;

class LoadTestDomainData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $domain1 = new Domain();
        $domain1->setDomain('domain.tld');
        $domain1->setEnabled(true);
    }

    public function getOrder()
    {
        return 2;
    }
}


