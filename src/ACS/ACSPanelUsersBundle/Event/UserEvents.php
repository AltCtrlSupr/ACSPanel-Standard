<?php
namespace ACS\ACSPanelUsersBundle\Event;

final class UserEvents
{
    /**
     * The user.register event is thrown each time an user is registered
     * in the system.
     *
     * The event listener receives an
     * ACS\ACSPanelBundle\Event\FilterUserEvent instance.
     *
     * @var string
     */
    const USER_REGISTER = 'user.register';
}
