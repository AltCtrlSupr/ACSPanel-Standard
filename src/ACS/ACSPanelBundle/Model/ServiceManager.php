<?php

namespace ACS\ACSPanelBundle\Model;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class ServiceManager
{
    private $aclManager;

    public function attachToUser($service, $user)
    {
        if (!$service || !$user) {
            return false;
        }

        $this->aclManager->addObjectPermission($service, MaskBuilder::MASK_VIEW, $user);

        return true;
    }

    public function setAclManager($manager)
    {
        $this->aclManager = $manager;
    }
}
