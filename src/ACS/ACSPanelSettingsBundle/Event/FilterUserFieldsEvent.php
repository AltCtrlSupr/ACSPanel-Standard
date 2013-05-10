<?php
namespace ACS\ACSPanelSettingsBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class FilterUserFieldsEvent extends Event
{
    protected $user_fields;
    protected $em;

    public function __construct($user_fields, $em)
    {
        $this->user_fields = $user_fields;
        $this->em = $em;
    }

}
