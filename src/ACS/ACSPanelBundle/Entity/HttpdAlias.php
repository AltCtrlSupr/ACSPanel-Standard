<?php

namespace ACS\ACSPanelBundle\Entity;

use Monolog\Logger;
use Doctrine\ORM\Mapping as ORM;

/**
 * ACS\ACSPanelBundle\Entity\HttpdAlias
 */
class HttpdAlias
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $alias
     */
    private $alias;


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
     * Set alias
     *
     * @param string $alias
     * @return HttpdAlias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    
        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }
    /**
     * @var boolean $enabled
     */
    private $enabled;

    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;

    /**
     * @var ACS\ACSPanelBundle\Entity\HttpdHost
     */
    private $httpd_host;


    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return HttpdAlias
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    
        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return HttpdAlias
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return HttpdAlias
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set httpd_host
     *
     * @param ACS\ACSPanelBundle\Entity\HttpdHost $httpdHost
     * @return HttpdAlias
     */
    public function setHttpdHost(\ACS\ACSPanelBundle\Entity\HttpdHost $httpdHost = null)
    {
        $this->httpd_host = $httpdHost;
    
        return $this;
    }

    /**
     * Get httpd_host
     *
     * @return ACS\ACSPanelBundle\Entity\HttpdHost 
     */
    public function getHttpdHost()
    {
        return $this->httpd_host;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
	    if(!$this->getCreatedAt())
	    {
		    $this->created_at = new \DateTime();
	    }
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
	    $this->updated_at = new \DateTime();
    }

    /**
     * @var \ACS\ACSPanelBundle\Entity\Domain
     */
    private $domain;


    /**
     * Set domain
     *
     * @param \ACS\ACSPanelBundle\Entity\Domain $domain
     * @return HttpdAlias
     */
    public function setDomain(\ACS\ACSPanelBundle\Entity\Domain $domain = null)
    {
        $this->domain = $domain;
    
        return $this;
    }

    /**
     * Get domain
     *
     * @return \ACS\ACSPanelBundle\Entity\Domain 
     */
    public function getDomain()
    {
        return $this->domain;
    }
}