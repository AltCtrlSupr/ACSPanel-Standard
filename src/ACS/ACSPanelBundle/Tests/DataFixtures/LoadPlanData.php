<?php
namespace ACS\ACSPanelBundle\Tests\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use ACS\ACSPanelBundle\Entity\Plan;

class LoadPlanData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $plan1 = new Plan();
        $plan1->setPlanName("Testing plan");
        $plan1->setMaxDomain(10);

        $manager->persist($plan1);
        $manager->flush();

        $this->addReference('test-plan', $plan1);
    }

    public function getOrder()
    {
        return 1;
    }
}
