<?php
namespace ACS\ACSPanelBackupBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ACS\ACSPanelBundle\Event\FilterUserEvent;

class UserSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'user.register'     => array('onUserRegister', 10)
        );
    }

    public function onUserRegister(FilterUserEvent $event)
    {
        //$user = $event->getUser();

        //echo $user->getName();
        //exit;

    }
}
