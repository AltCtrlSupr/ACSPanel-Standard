<?php
namespace ACS\ACSPanelBackupBundle\Listener;
use ACS\ACSPanelBundle\Event\FilterUserEvent;

class UserListener
{

    protected $user;

    public function onRegisterAction(FilterUserEvent $event)
    {
        $user = $event->getUser();
        echo "Trigger is executed";
        exit;
    }
}

