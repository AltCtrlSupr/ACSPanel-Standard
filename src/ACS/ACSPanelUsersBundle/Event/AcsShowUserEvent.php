<?php

namespace ACS\ACSPanelUsersBundle\Event;

use Avanzu\AdminThemeBundle\Event\ShowUserEvent;
use ACS\ACSPanelUsersBundle\Entity\User;

class AcsShowUserEvent
{
    private $security;

    public function onShowUser(ShowUserEvent $event)
    {
        $user = $this->getUser();
        $event->setUser($user);
    }

    protected function getUser()
    {
        return $this->security->getToken()->getUser();
    }

    public function setSecurity($security)
    {
        $this->security = $security;
    }

}
