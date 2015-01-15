<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;

use ACS\ACSPanelBundle\Entity\HttpdHost;

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
						$this->addUserOwnerPermission($user, $entity);
					}
				}

				$first_level_user_classes = array('DB', 'DatabaseUser', 'Domain', 'FtpdUser', 'IpAddress', 'LogItem', 'MailDomain', 'MailWBList', 'PanelSetting', 'Server', 'Service');
				foreach($first_level_user_classes as $class){
					if($entity instanceof $class){
						$user = $entity->getUser();
						$this->addUserOwnerPermission($user, $entity);
					}
				}

				if($entity instanceof DnsRecord){
					$user = $entity->getDnsDomain()->getDomain()->getUser();
					$this->addUserOwnerPermission($user, $entity);
				}

				if($entity instanceof HttpdUser){
					$user = $entity->getHttpdHost()->getDomain()->getUser();
					$this->addUserOwnerPermission($user, $entity);
				}

				if($entity instanceof MailLogrcvd){
					$user = $entity->getMailDomain()->getUser();
					$this->addUserOwnerPermission($user, $entity);
				}

				if($entity instanceof MailMailBox){
					$user = $entity->getMailDomain()->getUser();
					$this->addUserOwnerPermission($user, $entity);
				}

				if($entity instanceof UserPlan){
					$user = $entity->getPuser();
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
                    $aclManager->removeObjectPermission($entity, MaskBuilder::MASK_MASTER, $superadmin);
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
