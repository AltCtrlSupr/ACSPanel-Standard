<?php
namespace ACS\ACSPanelBundle\Event\Subscribers;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use ACS\ACSPanelBundle\Entity\DB;
use ACS\ACSPanelBundle\Entity\DatabaseUser;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class DBSubscriber implements EventSubscriber
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

        if ($entity instanceof DB){
            $this->removeDatabase($entity);
        }
        if ($entity instanceof DatabaseUser){
            $this->removeUserInDatabase($entity);
        }

        $aclManager = $this->container->get('problematic.acl_manager');

        if ($entity instanceOf \Gedmo\Loggable\Entity\LogEntry) {
            $user = array();
        } else {
            $user = $entity->getOwners();
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof DB){
            $this->createDatabase($entity);
            $this->setCreatedAtValue($entity);
            $this->setUserValue($entity);
        }

        if ($entity instanceof DatabaseUser){
            $this->setCreatedAtValue($entity);
            $this->setUserValue($entity);
            $this->createUserInDatabase($entity);
        }
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof DatabaseUser){
            $this->setUpdatedAtValue($entity);
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        // Adding master permissions to superadmins
        $superadmins = $em->getRepository('\ACS\ACSPanelUsersBundle\Entity\User')->getSuperadminUsers();
        $admins = $em->getRepository('\ACS\ACSPanelUsersBundle\Entity\User')->getAdminUsers();


        if ($entity instanceOf \Gedmo\Loggable\Entity\LogEntry) {
            $user = array();
        } else {
            $user = $entity->getOwners();
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof DatabaseUser){
            $this->removeUserInDatabase($entity);
            $this->createUserInDatabase($entity);
            $this->setUpdatedAtValue($entity);
        }
    }

    private function createUserInDatabase($entity)
    {
        $conn = $this->getConnection($entity->getDb());

        if ($conn) {
            $sql = "CREATE USER '".$entity->getUsername()."'@'%' IDENTIFIED BY '".$entity->getPassword()."'";
            $conn->executeQuery($sql);
            $sql = "GRANT ALL PRIVILEGES ON `".$entity->getDb()."`.* TO '".$entity->getUsername()."'@'%'";
            $conn->executeQuery($sql);
            $sql = "FLUSH PRIVILEGES";
            $conn->executeQuery($sql);
        }
    }

    public function removeUserInDatabase($entity)
    {
        $conn = $this->getConnection($entity->getDb());

        $sql = "DROP USER '".$entity->getUsername()."'@'%';";

        $conn->executeQuery($sql);
    }

    private function setCreatedAtValue($entity)
    {
        if(!$entity->getCreatedAt())
        {
            $entity->setCreatedAt( new \DateTime());
        }
    }

    private function setUpdatedAtValue($entity)
    {
        $entity->setUpdatedAt(new \DateTime());
    }

    public function createDatabase($entity)
    {
        $conn = $this->getConnection($entity);

        if (!$conn) {
            return;
        }

        $sql = "CREATE DATABASE IF NOT EXISTS ".$entity->getName()." DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
        $conn->executeQuery($sql);

        return $this;

    }

    public function setProtectedDir($entity)
    {
        $settings = $this->container->get('acs.setting_manager');
        $service = $this->container->get('security.context');

        $user = $service->getToken()->getUser();
        if (!$entity->getProtectedDir()) {
            return $entity->setProtectedDir($settings->getSystemSetting('home_base') . $user->getUsername() . '/web/' . $entity->getHttpdHost()->getDomain()->getDomain() . '/httpdocs');
        }

        return $entity;
    }

    public function removeDatabase($entity)
    {
        $conn = $this->getConnection($entity);

        $users = $entity->getDatabaseUsers();
        if (count($users)) {
            foreach ($users as $usr) {
                $sql = "DROP USER '".$usr->getUsername()."'@'%'";
                $conn->executeQuery($sql);
            }
        }

        $sql = "DROP DATABASE IF EXISTS ".$entity->getName();
        $conn->executeQuery($sql);

        return $entity;
    }

    public function setUserValue($entity)
    {
        if ($entity->getUser()) {
            return;
        }

        $service = $this->container->get('security.context');

        if (!$service->getToken()) {
            return;
        }

        $user = $service->getToken()->getUser();
        return $entity->setUser($user);
    }

    private function getConnection($entity)
    {
        if (!$entity->getService()) {
            return;
        }

        $settings = $entity->getService()->getSettings();

        $admin_user = '';
        $admin_password = '';

        foreach ($settings as $setting){
            if($setting->getSettingKey() == 'admin_user') {
                $admin_user = $setting->getValue();
            }
            if($setting->getSettingKey() == 'admin_password') {
                $admin_password = $setting->getValue();
            }
        }

        $server_ip = $entity->getService()->getIp();

        $config = new \Doctrine\DBAL\Configuration();

        $connectionParams = array(
            'user' => $admin_user,
            'password' => $admin_password,
            'host' => $server_ip,
            'driver' => 'pdo_mysql',
        );
        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

        return $conn;
    }
}
