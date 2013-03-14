<?php

namespace ACS\ACSPanelSettingsBundle\Modules;

use Symfony\Component\DependencyInjection\Container;

use ACS\ACSPanelSettingsBundle\Entity\ConfigSetting;
use ACS\ACSPanelBundle\Entity\PanelSetting;
use ACS\ACSPanelBundle\Entity\Server;

use Doctrine\ORM\EntityManager;

class SystemSettings
{
    private $container;

    public function __construct(Container $container)
    {
    }

}
