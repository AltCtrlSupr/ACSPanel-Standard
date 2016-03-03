<?php

namespace ACS\ACSPanelAnsibleBundle\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\AccessType("public_method")
 */
class Inventory
{
    /**
     * @JMS\Groups({"inventory"})
     * @JMS\Inline()
     */
    private $groups;

    /**
     * @JMS\Groups({"inventory"})
     * @JMS\SerializedName("_meta")
     */
    private $meta;

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
}
