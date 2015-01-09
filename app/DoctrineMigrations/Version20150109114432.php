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
class Version20150109114432 extends AbstractMigration implements ContainerAwareInterface
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

        // Adding master permissions to superadmins
		$superadmins = $em->getRepository('ACS\ACSPanelUsersBundle\Entity\FosUser')->getSuperadminUsers();

        $entity_classes = array('DatabaseUser', 'DB', 'DnsDomain', 'DnsRecord', 'Domain', 'FieldType', 'FtpdUser', 'HttpdHost', 'HttpdUser', 'IpAddress', 'MailAlias', 'MailDomain', 'MailLogrcvd', 'MailMailbox', 'MailWBList', 'PanelSetting', 'Plan', 'Server', 'Service', 'ServiceType', 'UserPlan');

        foreach ($entity_classes as $eclass){
            $class_rep = "ACS\ACSPanelBundle\Entity\\$eclass";
            $entities = $em->getRepository($class_rep)->findAll();
            foreach($entities as $entity){
                foreach($superadmins as $superadmin){
                    $aclManager->addObjectPermission($entity, MaskBuilder::MASK_MASTER, $superadmin);
                }
            }
        }

        $entity_classes = array('FosUser', 'FosGroup');

        foreach ($entity_classes as $eclass){
            $class_rep = "ACS\ACSPanelUsersBundle\Entity\\$eclass";
            $entities = $em->getRepository($class_rep)->findAll();
            foreach($entities as $entity){
                foreach($superadmins as $superadmin){
                    $aclManager->addObjectPermission($entity, MaskBuilder::MASK_MASTER, $superadmin);
                }
            }
        }

    }

    public function down(Schema $schema)
    {
		$aclManager = $this->container->get('problematic.acl_manager');
		$em = $this->container->get('doctrine.orm.entity_manager');

        // Adding master permissions to superadmins
		$superadmins = $em->getRepository('ACS\ACSPanelUsersBundle\Entity\FosUser')->getSuperadminUsers();

        $entity_classes = array('DatabaseUser', 'DB', 'DnsDomain', 'DnsRecord', 'Domain', 'FieldType', 'FtpdUser', 'HttpdHost', 'HttpdUser', 'IpAddress', 'MailAlias', 'MailDomain', 'MailLogrcvd', 'MailMailbox', 'MailWBList', 'PanelSetting', 'Plan', 'Server', 'Service', 'ServiceType', 'UserPlan');

        foreach ($entity_classes as $eclass){
            $class_rep = "ACS\ACSPanelBundle\Entity\\$eclass";
            $entities = $em->getRepository($class_rep)->findAll();
            foreach($entities as $entity){
                foreach($superadmins as $superadmin){
                    $aclManager->revokePermission($entity, MaskBuilder::MASK_MASTER, $superadmin);
                }
            }
        }

        $entity_classes = array('FosUser', 'FosGroup');

        foreach ($entity_classes as $eclass){
            $class_rep = "ACS\ACSPanelUsersBundle\Entity\\$eclass";
            $entities = $em->getRepository($class_rep)->findAll();
            foreach($entities as $entity){
                foreach($superadmins as $superadmin){
                    $aclManager->revokePermission($entity, MaskBuilder::MASK_MASTER, $superadmin);
                }
            }
        }


    }
}
