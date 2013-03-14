<?php

namespace ACS\ACSPanelBackupBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use ACS\ACSPanelBackupBundle\Listener\UserListener;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ACSACSPanelBackupBundle extends Bundle
{
    public function boot()
    {
        $dispatcher = new EventDispatcher();
        //$dispatcher = $this->container->get('event_dispatcher');

        $dispatcher->addListener('user.register', array(new UserListener(), 'onRegisterAction'));
    }
}
