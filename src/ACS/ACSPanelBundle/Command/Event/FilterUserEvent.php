<?php
namespace ACS\ACSPanelBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use ACS\ACSPanelBundle\Entity\FosUser;

class FilterUserEvent extends Event
{
    protected $user;
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function setUsers($users)
    {
        $this->users = $users;
    }

    public function setUser(FosUser $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
}
