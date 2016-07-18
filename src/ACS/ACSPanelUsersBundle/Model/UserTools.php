<?php
namespace ACS\ACSPanelUsersBundle\Model;

use Symfony\Component\DependencyInjection\Container;

use ACS\ACSPanelSettingsBundle\Entity\ConfigSetting;
use ACS\ACSPanelBundle\Entity\PanelSetting;
use ACS\ACSPanelBundle\Entity\Server;

use Doctrine\ORM\EntityManager;

class UserTools
{
    private $initialized = false;
    private $em;
    private $container;

    public function __construct(EntityManager $entityManager,Container $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

    /**
     * Returns true if uid exists in ftpduser table
     * @return boolean
     */
    public function getAvailableGid()
    {
        $container = $this->container;
        $setting_manager = $container->get('acs.setting_manager');

        $last_gid = $setting_manager->getInternalSetting('last_used_gid');

        if($last_gid)
            return $last_gid+1;

        $start_gid = $setting_manager->getSystemSetting('start_gid');
        return $start_gid + 1;
    }

    /**
     * Returns true if uid exists in ftpduser table
     * @return boolean
     */
    public function getAvailableUid()
    {
        $container = $this->container;
        $setting_manager = $container->get('acs.setting_manager');

        $last_uid = $setting_manager->getInternalSetting('last_used_uid');

        if($last_uid)
            return $last_uid+1;

        $start_uid = $setting_manager->getSystemSetting('start_uid');
        return $start_uid + 1;
    }

    /**
     * Increments the last used uid setting
     */
    public function incrementLastUidSetting()
    {
        $container = $this->container;
        $setting_manager = $container->get('acs.setting_manager');
        $last_uid = $setting_manager->getSystemSetting('last_used_uid');
        return $setting_manager->setInternalSetting('last_used_uid', $last_uid + 1);

    }

    /**
     * Increments the last used gid setting
     */
    public function incrementLastGidSetting()
    {
        $container = $this->container;
        $setting_manager = $container->get('acs.setting_manager');
        $last_gid = $setting_manager->getSystemSetting('last_used_gid');

        return $setting_manager->setInternalSetting('last_used_gid', $last_gid + 1);
    }
}
