<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150108174254 extends AbstractMigration implements ContainerAwareInterface
{

	private $container;

	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}

    public function up(Schema $schema)
    {
		$aclManager = $this->container->get('problematic.acl_manager');
		$em = $this->container->get('doctrine.orm.entity_manager');

		$httpdhosts = $em->getRepository('ACS\ACSPanelBundle\Entity\HttpdHost')->findAll();
		foreach($httpdhosts as $httpdhost){
			$user = $httpdhost->getDomain()->getUser();
			$aclManager->addObjectPermission($httpdhost, MaskBuilder::MASK_OWNER, $user);
		}
    }

    public function down(Schema $schema)
    {
		$aclManager = $this->container->get('problematic.acl_manager');
		$em = $this->container->get('doctrine.orm.entity_manager');

		$httpdhosts = $em->getRepository('ACS\ACSPanelBundle\Entity\HttpdHost')->findAll();
		foreach($httpdhosts as $httpdhost){
			$user = $httpdhost->getDomain()->getUser();
			$aclManager->revokePermission($httpd_host, MaskBUILDER::MASK_OWNER, $user);
		}
    }
}
