<?php

namespace ACS\ACSPanelBundle\Model\Entity;

interface AclEntity
{
    /**
     * It gives responsability to each entity to
     * know who are the owner
     *
     * @return mixed Array|String|Object
     */
    public function getOwners();

    // public function getAllowedToUse();
}
