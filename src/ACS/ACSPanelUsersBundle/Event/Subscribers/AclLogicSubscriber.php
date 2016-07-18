<?php
namespace ACS\ACSPanelUsersBundle\Event\Subscribers;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use ACS\ACSPanelUsersBundle\Entity\User;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class AclLogicSubscriber implements EventSubscriber
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

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

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        $aclManager = $this->container->get('problematic.acl_manager');
        $aclManager->deleteAclFor($entity);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if($this->getCurrentUser()){
            $aclManager = $this->container->get('problematic.acl_manager');
            $aclManager->addObjectPermission($entity, MaskBuilder::MASK_OWNER, $this->getCurrentUser());
            if($this->getCurrentUser()->getParentUser()){
                $aclManager->addObjectPermission($entity, MaskBuilder::MASK_MASTER, $this->getCurrentUser()->getParentUser());
            }
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
    }

    public function getCurrentUser()
    {
        $service = $this->container->get('security.context');

        if(!$service->getToken())
            return;

        $user = $service->getToken()->getUser();

        return $user;
    }
}
