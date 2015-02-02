<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;

use ACS\ACSPanelBundle\Entity\HttpdHost;
use ACS\ACSPanelBundle\Entity\DatabaseUser;
use ACS\ACSPanelBundle\Entity\DB;
use ACS\ACSPanelBundle\Entity\DnsDomain;
use ACS\ACSPanelBundle\Entity\DnsRecord;
use ACS\ACSPanelBundle\Entity\Domain;
use ACS\ACSPanelBundle\Entity\FieldType;
use ACS\ACSPanelBundle\Entity\FtpdUser;
use ACS\ACSPanelBundle\Entity\HttpdUser;
use ACS\ACSPanelBundle\Entity\IpAddress;
use ACS\ACSPanelBundle\Entity\MailAlias;
use ACS\ACSPanelBundle\Entity\MailDomain;
use ACS\ACSPanelBundle\Entity\MailLogrcvd;
use ACS\ACSPanelBundle\Entity\MailMailbox;
use ACS\ACSPanelBundle\Entity\MailWBList;
use ACS\ACSPanelBundle\Entity\PanelSetting;
use ACS\ACSPanelBundle\Entity\Plan;
use ACS\ACSPanelBundle\Entity\Server;
use ACS\ACSPanelBundle\Entity\Service;
use ACS\ACSPanelBundle\Entity\ServiceType;
use ACS\ACSPanelBundle\Entity\UserPlan;

/**
 */
class Version20150108135726 extends AbstractMigration implements ContainerAwareInterface
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

				$domain_related_user_classes = array('HttpdHost', 'DnsDomain', 'MailAlias');
				foreach($domain_related_user_classes as $class){
					if($entity instanceof $class){
						$user = $entity->getDomain()->getUser();
						if($user)
							$this->addUserOwnerPermission($user, $entity);
					}
				}

				$first_level_user_classes = array('DB', 'DatabaseUser', 'Domain', 'FtpdUser', 'IpAddress', 'LogItem', 'MailDomain', 'MailWBList', 'PanelSetting', 'Server', 'Service');
				foreach($first_level_user_classes as $class){
					if($entity instanceof $class){
						$user = $entity->getUser();
						if($user)
							$this->addUserOwnerPermission($user, $entity);
					}
				}

				if($entity instanceof DnsRecord){
					$user = $entity->getDnsDomain()->getDomain()->getUser();
					if($user)
						$this->addUserOwnerPermission($user, $entity);
				}

				if($entity instanceof HttpdUser){
					$user = $entity->getHttpdHost()->getDomain()->getUser();
					if($user)
						$this->addUserOwnerPermission($user, $entity);
				}

				if($entity instanceof MailLogrcvd){
					$user = $entity->getMailDomain()->getUser();
					if($user)
						$this->addUserOwnerPermission($user, $entity);
				}

				if($entity instanceof MailMailBox){
					$user = $entity->getMailDomain()->getUser();
					if($user)
						$this->addUserOwnerPermission($user, $entity);
				}

				if($entity instanceof UserPlan){
					$user = $entity->getPuser();
					if($user)
						$this->addUserOwnerPermission($user, $entity);
				}


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

	public function addUserOwnerPermission($user, $entity)
	{
		$aclManager = $this->container->get('problematic.acl_manager');

		if($parent = $user->getParentUser())
			$aclManager->addObjectPermission($entity, MaskBuilder::MASK_MASTER, $parent);

		$aclManager->addObjectPermission($entity, MaskBuilder::MASK_OWNER, $user);
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

				$domain_related_user_classes = array('HttpdHost', 'DnsDomain', 'MailAlias');
				foreach($domain_related_user_classes as $class){
					if($entity instanceof $class){
						$user = $entity->getDomain()->getUser();
						$this->removeUserOwnerPermission($user, $entity);
					}
				}

				$first_level_user_classes = array('DB', 'DatabaseUser', 'Domain', 'FtpdUser', 'IpAddress', 'LogItem', 'MailDomain', 'MailWBList', 'PanelSetting', 'Server', 'Service');
				foreach($first_level_user_classes as $class){
					if($entity instanceof $class){
						$user = $entity->getUser();
						$this->removeUserOwnerPermission($user, $entity);
					}
				}

				if($entity instanceof DnsRecord){
					$user = $entity->getDnsDomain()->getDomain()->getUser();
					$this->removeUserOwnerPermission($user, $entity);
				}

				if($entity instanceof HttpdUser){
					$user = $entity->getHttpdHost()->getDomain()->getUser();
					$this->removeUserOwnerPermission($user, $entity);
				}

				if($entity instanceof MailLogrcvd){
					$user = $entity->getMailDomain()->getUser();
					$this->removeUserOwnerPermission($user, $entity);
				}

				if($entity instanceof MailMailBox){
					$user = $entity->getMailDomain()->getUser();
					$this->removeUserOwnerPermission($user, $entity);
				}

				if($entity instanceof UserPlan){
					$user = $entity->getPuser();
					$this->removeUserOwnerPermission($user, $entity);
				}


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

	public function removeUserOwnerPermission($user, $entity)
	{
		$aclManager = $this->container->get('problematic.acl_manager');

		if($parent = $user->getParentUser())
			$aclManager->revokePermission($entity, MaskBuilder::MASK_MASTER, $parent);

		$aclManager->revokePermission($entity, MaskBuilder::MASK_OWNER, $user);
	}

}
