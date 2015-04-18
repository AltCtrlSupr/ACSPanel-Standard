<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150418153537 extends AbstractMigration implements ContainerAwareInterface
{
	private $container;

	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
		$em = $this->container->get('doctrine.orm.entity_manager');

		$settings = $em->getRepository('\ACS\ACSPanelBundle\Entity\PanelSetting')->findAll();
        foreach($settings as $setting) {
            if($setting->getSettingKey() == "user_theme" && $setting->getValue() == "terminal"){
                $setting->setValue("default");
                $em->persist($setting);
            }
        }
        $em->flush();
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        echo "This migration is not reversible";
    }
}
