<?php

namespace ACS\ACSPanelBundle\Entity;

use Monolog\Logger;
use Doctrine\ORM\Mapping as ORM;

/**
 * ACS\ACSPanelBundle\Entity\PdnsRecord
 */
class PdnsRecord
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $domainId
     */
    private $domainId;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $type
     */
    private $type;

    /**
     * @var string $content
     */
    private $content;

    /**
     * @var integer $ttl
     */
    private $ttl;

    /**
     * @var integer $prio
     */
    private $prio;

    /**
     * @var integer $changeDate
     */
    private $changeDate;


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
     * Set name
     *
     * @param string $name
     * @return PdnsRecord
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return PdnsRecord
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return PdnsRecord
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set ttl
     *
     * @param integer $ttl
     * @return PdnsRecord
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;
    
        return $this;
    }

    /**
     * Get ttl
     *
     * @return integer 
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * Set prio
     *
     * @param integer $prio
     * @return PdnsRecord
     */
    public function setPrio($prio)
    {
        $this->prio = $prio;
    
        return $this;
    }

    /**
     * Get prio
     *
     * @return integer 
     */
    public function getPrio()
    {
        return $this->prio;
    }

    /**
     * Set changeDate
     *
     * @param integer $changeDate
     * @return PdnsRecord
     */
    public function setChangeDate($changeDate)
    {
        $this->changeDate = $changeDate;
    
        return $this;
    }

    /**
     * Get changeDate
     *
     * @return integer 
     */
    public function getChangeDate()
    {
        return $this->changeDate;
    }
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PdnsRecord
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
     * @return PdnsRecord
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
     * @var \ACS\ACSPanelBundle\Entity\PdnsDomain
     */
    private $dns_domain;


    /**
     * Set dns_domain
     *
     * @param \ACS\ACSPanelBundle\Entity\PdnsDomain $dnsDomain
     * @return PdnsRecord
     */
    public function setDnsDomain(\ACS\ACSPanelBundle\Entity\PdnsDomain $dnsDomain = null)
    {
        $this->dns_domain = $dnsDomain;
    
        return $this;
    }

    /**
     * Get dns_domain
     *
     * @return \ACS\ACSPanelBundle\Entity\PdnsDomain 
     */
    public function getDnsDomain()
    {
        return $this->dns_domain;
    }

    public function __toString()
    {
        return $this->getName();
    }
    /**
     * @var \ACS\ACSPanelBundle\Entity\Domain
     */
    private $domain;


    /**
     * Set domain
     *
     * @param \ACS\ACSPanelBundle\Entity\Domain $domain
     * @return PdnsRecord
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