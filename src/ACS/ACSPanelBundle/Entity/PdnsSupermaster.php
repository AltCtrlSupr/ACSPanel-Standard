<?php

namespace ACS\ACSPanelBundle\Entity;

use Monolog\Logger;
use Doctrine\ORM\Mapping as ORM;

/**
 * ACS\ACSPanelBundle\Entity\PdnsSupermaster
 */
class PdnsSupermaster
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $ip
     */
    private $ip;

    /**
     * @var string $nameserver
     */
    private $nameserver;

    /**
     * @var string $account
     */
    private $account;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return PdnsSupermaster
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set nameserver
     *
     * @param string $nameserver
     * @return PdnsSupermaster
     */
    public function setNameserver($nameserver)
    {
        $this->nameserver = $nameserver;
    
        return $this;
    }

    /**
     * Get nameserver
     *
     * @return string 
     */
    public function getNameserver()
    {
        return $this->nameserver;
    }

    /**
     * Set account
     *
     * @param string $account
     * @return PdnsSupermaster
     */
    public function setAccount($account)
    {
        $this->account = $account;
    
        return $this;
    }

    /**
     * Get account
     *
     * @return string 
     */
    public function getAccount()
    {
        return $this->account;
    }

}