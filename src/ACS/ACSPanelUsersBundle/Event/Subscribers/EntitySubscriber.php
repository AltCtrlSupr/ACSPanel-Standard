<?php
namespace ACS\ACSPanelUsersBundle\Event\Subscribers;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use ACS\ACSPanelUsersBundle\Entity\User;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class EntitySubscriber implements EventSubscriber
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
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof User){
            $this->setUserUserValue($entity);
            $this->setGidAndUidValues($entity);
        }
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

        if ($entity instanceof User){
            $this->incrementUidSetting($entity);
            $this->incrementGidSetting($entity);
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


    public function setUserUserValue($entity)
    {
        if($entity->getParentUser())
            return;


        $service = $this->container->get('security.context');

        if ($service) {
            if ($service->getToken()) {
                $user = $service->getToken()->getUser();
                // TODO: Get system user and set if its register from register form
                if($user != 'anon.'){
                    return $entity->setParentUser($user);
                }else{
                    // $system_user = new User();
                    // $system_user->setId(1);
                    // return $this->setParentUser($system_user);
                }
            }
        }
    }

    public function setGidAndUidValues($entity)
    {
        $usertools = $this->container->get('acs.user.tools');

        $entity->setUid($usertools->getAvailableUid());
        $entity->setGid($usertools->getAvailableGid());
    }

    public function incrementUidSetting($entity)
    {
        $setting_manager = $this->container->get('acs.setting_manager');

        return $setting_manager->setInternalSetting('last_used_uid',$entity->getUid());
    }

   public function incrementGidSetting($entity)
    {
        $setting_manager = $this->container->get('acs.setting_manager');

        return $setting_manager->setInternalSetting('last_used_gid',$entity->getGid());
    }

}
