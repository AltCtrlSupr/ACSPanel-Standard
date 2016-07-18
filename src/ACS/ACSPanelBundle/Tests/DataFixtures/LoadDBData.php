<?php
namespace ACS\ACSPanelBundle\Tests\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use ACS\ACSPanelBundle\Entity\DB;

class LoadDBData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // Adding 15 db for superadmin
        for ($i=0; $i < 15; $i++) {
            $db = new DB();
            $db->setUser($this->getReference('user-super-admin'));
            $db->setName('db_' . $i);

            $manager->persist($db);
            $this->addReference('db-' . $i, $db);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
