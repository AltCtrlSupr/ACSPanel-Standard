<?php
namespace ACS\ACSPanelSettingsBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class FilterUserFieldsEvent extends Event
{
    protected $user_fields;
    protected $container;

    public function __construct($user_fields, $container)
    {
        $this->user_fields = $user_fields;
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getUserFields()
    {
        return $this->user_fields;
    }
}
