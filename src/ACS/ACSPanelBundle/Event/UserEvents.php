<?php
namespace ACS\ACSPanelBundle\Event;

final class UserEvents
{
    /**
     * The user.register event is thrown each time an user is registered
     * in the system.
     *
     * The event listener receives an
     * @todo: create the event instance
     * ACS\ACSPanelBundle\Event\FilterUserEvent instance.
     *
     * @var string
     */
    const USER_REGISTER = 'user.register';
}
