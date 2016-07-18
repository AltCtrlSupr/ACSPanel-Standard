<?php
namespace ACS\ACSPanelBundle\Tests\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use ACS\ACSPanelBundle\Entity\Service;
use ACS\ACSPanelBundle\Entity\FieldType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadServiceData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        // Ftpdservice types
        $ftpdservice = new Service();
        $ftpdservice->setName('Ftpd Testing Service');
        $ftpdservice->setType($this->getReference('ftpd-service-type'));
        $ftpdservice->setServer($this->getReference('server-1'));
        $manager->persist($ftpdservice);

        $reseller = $this->getReference('user-reseller');
        $user = $this->getReference('user-reseller');

        $serviceManager = $this->container->get('service_manager');
        if ($user) {
            $serviceManager->attachToUser($ftpdservice, $user);
        }
        if ($reseller) {
            $serviceManager->attachToUser($ftpdservice, $reseller);
        }

        $webservice = new Service();
        $webservice->setName('web.acs.li');
        $webservice->setServer($this->getReference('server-1'));
        $webservice->setUser($this->getReference('user-super-admin'));
        $manager->persist($webservice);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
