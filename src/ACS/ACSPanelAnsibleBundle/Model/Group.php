<?php

namespace ACS\ACSPanelAnsibleBundle\Model;

use JMS\Serializer\Annotation as JMS;

class Group
{
    /**
     * @JMS\Groups({"inventory"})
     */
    private $hosts;

    /**
     * @JMS\Groups({"inventory"})
     */
    private $vars;

    public function __construct()
    {
        $this->hosts = array();
        // $this->vars = array();
    }

    public function getVars()
    {
        return $this->vars;
    }

    public function setVars($vars)
    {
        $this->vars = $vars;
    }

    public function setHosts($hosts)
    {
        $this->hosts = $hosts;
    }

    public function addHost($host)
    {
        if (!in_array($host, $this->hosts)) {
            $this->hosts[] = $host;
        }
    }

}
