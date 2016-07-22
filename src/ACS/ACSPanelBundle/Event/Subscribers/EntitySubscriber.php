<?php
namespace ACS\ACSPanelBundle\Event\Subscribers;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use ACS\ACSPanelBundle\Entity\Domain;
use ACS\ACSPanelBundle\Entity\HttpdHost;
use ACS\ACSPanelBundle\Entity\HttpdUser;
use ACS\ACSPanelBundle\Entity\FtpdUser;
use ACS\ACSPanelBundle\Entity\IpAddress;
use ACS\ACSPanelBundle\Entity\MailDomain;
use ACS\ACSPanelBundle\Entity\MailWBList;
use ACS\ACSPanelBundle\Entity\PanelSetting;
use ACS\ACSPanelBundle\Entity\Server;
use ACS\ACSPanelBundle\Entity\Service;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class EntitySubscriber implements EventSubscriber
{

    protected $container;

    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'postPersist',
            'postUpdate',
            'preUpdate',
            'preRemove',
            'postRemove',
        );
    }

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        $em = $args->getEntityManager();
        // Adding master permissions to superadmins
        $superadmins = $em->getRepository('\ACS\ACSPanelUsersBundle\Entity\User')->getSuperadminUsers();
        $admins = $em->getRepository('\ACS\ACSPanelUsersBundle\Entity\User')->getAdminUsers();

        $aclManager = $this->container->get('problematic.acl_manager');

        if ($entity instanceOf \Gedmo\Loggable\Entity\LogEntry) {
            $user = array();
        } else {
            $user = $entity->getOwners();
        }

        // If we get a single user we add him to object owner
        if (is_object($user)) {
            $this->removeUserOwnerPermission($user, $entity);
        }

        // If we receive an array we iterate it
        if (is_array($user)) {
            foreach ($user as $owner) {
                $this->removeUserOwnerPermission($owner, $entity);
            }
        }

        // If owners are all admins
        if ($user == 'admins') {
            foreach ($admins as $admin) {
                $this->removeUserOwnerPermission($admin, $entity);
            }
        }

        foreach ($superadmins as $superadmin) {
            $aclManager->deleteAclFor($entity);
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Domain){
            $this->setUserValue($entity);
        }

        if ($entity instanceof FtpdUser){
            $this->setUserValue($entity);
            $usertools = $this->container->get('acs.user.tools');
            $entity->setUid($usertools->getAvailableUid());
            $entity->setGid($usertools->getAvailableGid());

            // Fixing integrity constraint null
            if (!$entity->getDir()) {
                $entity->setDir('');
            }
        }

        if ($entity instanceof HttpdUser){
            $this->setProtectedDir($entity);
        }

        if ($entity instanceof IpAddress){
            $this->setUserValue($entity);
        }

        if ($entity instanceof MailDomain){
            $this->setUserValue($entity);
            $settings_manager = $this->container->get('acs.setting_manager');
            $mail_domain_transport = $settings_manager->getSystemSetting('mail_domain_transport');
            if($mail_domain_transport){
                $entity->setTransport($mail_domain_transport);
            }
        }

        if ($entity instanceof MailWBList){
            $this->setUserValue($entity);
        }

        if ($entity instanceof PanelSetting){
            $this->setUserValue($entity);
        }
        if ($entity instanceof Server){
            $this->setUserValue($entity);
        }
        if ($entity instanceof Service){
            $this->setUserValue($entity);
            if (!$entity->getIp()) {
                if ($entity->getServer()) {
                    $entity->setIp($entity->getServer()->getIp());
                }
            }
        }
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        // Adding master permissions to superadmins
        $superadmins = $em->getRepository('\ACS\ACSPanelUsersBundle\Entity\User')->getSuperadminUsers();
        $admins = $em->getRepository('\ACS\ACSPanelUsersBundle\Entity\User')->getAdminUsers();


        $aclManager = $this->container->get('problematic.acl_manager');

        if ($entity instanceOf \Gedmo\Loggable\Entity\LogEntry) {
            $user = array();
        } else {
            $user = $entity->getOwners();
        }

        // If we get a single user we add him to object owner
        if (is_object($user)) {
            $this->addUserOwnerPermission($user, $entity);
        }

        // If we receive an array we iterate it
        if (is_array($user)) {
            foreach ($user as $owner) {
                $this->addUserOwnerPermission($owner, $entity);
            }
        }

        // If owners are all admins
        if ($user == 'admins') {
            foreach ($admins as $admin) {
                $this->addUserOwnerPermission($admin, $entity);
            }
        }

        foreach ($superadmins as $superadmin) {
            $aclManager->addObjectPermission($entity, MaskBuilder::MASK_MASTER, $superadmin);
        }

    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Domain){
            $this->setUpdatedAtValue($entity);
        }
        if ($entity instanceof HttpdHost){
            $this->setUpdatedAtValue($entity);
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
    }

    private function setCreatedAtValue($entity)
    {
        if (!$entity->getCreatedAt()) {
            $entity->setCreatedAt( new \DateTime());
        }
    }

    private function setUpdatedAtValue($entity)
    {
        $entity->setUpdatedAt(new \DateTime());
    }

    public function setProtectedDir($entity)
    {
        $settings = $this->container->get('acs.setting_manager');
        $service = $this->container->get('security.token_storage');

        $user = $service->getToken()->getUser();
        if (!$entity->getProtectedDir()) {
            return $entity->setProtectedDir($settings->getSystemSetting('home_base') . $user->getUsername() . '/web/' . $entity->getHttpdHost()->getDomain()->getDomain() . '/httpdocs');
        }

        return $entity;
    }

    public function setUserValue($entity)
    {
        if ($entity->getUser()) {
            return;
        }
        $service = $this->container->get('security.token_storage');
        if (!$service->getToken()) {
            return;
        }
        $user = $service->getToken()->getUser();
        return $entity->setUser($user);
    }

	public function addUserOwnerPermission($user, $entity)
	{
        $aclManager = $this->container->get('problematic.acl_manager');

        if ($parent = $user->getParentUser()) {
            $aclManager->addObjectPermission($entity, MaskBuilder::MASK_MASTER, $parent);
        }

        $aclManager->addObjectPermission($entity, MaskBuilder::MASK_OWNER, $user);
	}

    public function removeUserOwnerPermission($user, $entity)
    {
        $aclManager = $this->container->get('problematic.acl_manager');

        if ($parent = $user->getParentUser()) {
            $aclManager->revokePermission($entity, MaskBuilder::MASK_MASTER, $parent);
        }

        $aclManager->revokePermission($entity, MaskBuilder::MASK_OWNER, $user);
    }
}
