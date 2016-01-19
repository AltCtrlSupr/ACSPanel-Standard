<?php

namespace ACS\ACSPanelAnsibleBundle\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\AccessType("public_method")
 */
class Inventory
{
    private $meta;

    /**
     * @JMS\Groups({"inventory"})
     * @JMS\Inline()
     */
    private $groups;

    /**
     * @JMS\Groups({"inventory"})
     */
    private $hosts;

    public function getMeta()
    {
        return $this->meta;
    }

    public function setMeta($meta)
    {
        $this->meta = $meta;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

    public function getHosts()
    {
        return $this->hosts;
    }

    public function setHosts($hosts)
    {
        $this->hosts = $hosts;
    }
}
