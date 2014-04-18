<?php

namespace ACS\ACSPanelBundle\Modules;

use Symfony\Component\DependencyInjection\Container;

use ACS\ACSPanelSettingsBundle\Entity\ConfigSetting;
use ACS\ACSPanelBundle\Entity\PanelSetting;
use ACS\ACSPanelBundle\Entity\Server;

use Doctrine\ORM\EntityManager;

class ServerActions
{
    private $initialized = false;
    private $em;
    private $container;

    public function __construct(EntityManager $entityManager,Container $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

    public function setWebserverToReload(Server $server)
    {
        $em = $this->em;

        $config = $this->container->get('acs.setting_manager')->findBy(array('server' => $server, 'setting_key' => 'webserver-restart'));
        if(count($config))
            foreach($config as $config_value)
                $entity = $config_value;
        else{
            $entity = new PanelSetting();
            $entity->setServer($server);
            $entity->setContext('server-actions');
            $entity->setFocus('internal');
            $entity->setSettingKey('webserver-restart');
        }

        $entity->setContext('server-actions');
        $entity->setValue('true');

        $em->persist($entity);
        $em->flush();
    }


}
