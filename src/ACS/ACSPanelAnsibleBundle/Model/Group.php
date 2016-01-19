<?php

namespace ACS\ACSPanelAnsibleBundle\Model;

use JMS\Serializer\Annotation as JMS;

class Group
{
    /**
     * @JMS\Groups({"inventory"})
     */
    private $vars;

    /**
     * @JMS\Groups({"inventory"})
     */
    private $hosts;

    public function getVars()
    {
        return $this->vars;
    }

    public function setVars($vars)
    {
    }

    public function setHosts($hosts)
    {
        $this->hosts = $hosts;
    }

    public function addHost($host)
    {
        $this->hosts[] = $host;
    }

}
