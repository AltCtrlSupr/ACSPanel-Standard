<?php

namespace ACS\ACSPanelBundle\Entity;

use Monolog\Logger;
use Doctrine\ORM\Mapping as ORM;

/**
 * ACS\ACSPanelBundle\Entity\PdnsDomain
 */
class PdnsDomain
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $master
     */
    private $master;

    /**
     * @var integer $lastCheck
     */
    private $lastCheck;

    /**
     * @var string $type
     */
    private $type;

    /**
     * @var integer $notifiedSerial
     */
    private $notifiedSerial;

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
     * Set name
     *
     * @param string $name
     * @return PdnsDomain
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
     * Set master
     *
     * @param string $master
     * @return PdnsDomain
     */
    public function setMaster($master)
    {
        $this->master = $master;
    
        return $this;
    }

    /**
     * Get master
     *
     * @return string 
     */
    public function getMaster()
    {
        return $this->master;
    }

    /**
     * Set lastCheck
     *
     * @param integer $lastCheck
     * @return PdnsDomain
     */
    public function setLastCheck($lastCheck)
    {
        $this->lastCheck = $lastCheck;
    
        return $this;
    }

    /**
     * Get lastCheck
     *
     * @return integer 
     */
    public function getLastCheck()
    {
        return $this->lastCheck;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return PdnsDomain
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
     * Set notifiedSerial
     *
     * @param integer $notifiedSerial
     * @return PdnsDomain
     */
    public function setNotifiedSerial($notifiedSerial)
    {
        $this->notifiedSerial = $notifiedSerial;
    
        return $this;
    }

    /**
     * Get notifiedSerial
     *
     * @return integer 
     */
    public function getNotifiedSerial()
    {
        return $this->notifiedSerial;
    }

    /**
     * Set account
     *
     * @param string $account
     * @return PdnsDomain
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
     * @var ACS\ACSPanelBundle\Entity\FosUser
     */
    private $user;


    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return PdnsDomain
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
     * @return PdnsDomain
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
     * @return PdnsDomain
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
     * Set user
     *
     * @param ACS\ACSPanelBundle\Entity\FosUser $user
     * @return PdnsDomain
     */
    public function setUser(\ACS\ACSPanelBundle\Entity\FosUser $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return ACS\ACSPanelBundle\Entity\FosUser 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
	    if(!$this->getCreatedAt())
	    {
		    $this->createdAt = new \DateTime();
	    }
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
	    $this->updatedAt = new \DateTime();
    }

    /**
     * @var \ACS\ACSPanelBundle\Entity\Service
     */
    private $service;


    /**
     * Set service
     *
     * @param \ACS\ACSPanelBundle\Entity\Service $service
     * @return PdnsDomain
     */
    public function setService(\ACS\ACSPanelBundle\Entity\Service $service = null)
    {
        $this->service = $service;
    
        return $this;
    }

    /**
     * Get service
     *
     * @return \ACS\ACSPanelBundle\Entity\Service 
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @ORM\PrePersist
     */
    public function setUserValue()
    {
		global $kernel;

		if ('AppCache' == get_class($kernel)) {
			$kernel = $kernel->getKernel();
		}

		$service = $kernel->getContainer()->get('security.context');

        // Add your code here
		$user = $service->getToken()->getUser();
		return $this->setUser($user);
    }


    public function __toString()
    {
        return $this->getName();
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $dnsrecords;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dnsrecords = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add dnsrecords
     *
     * @param \ACS\ACSPanelBundle\Entity\PdnsRecord $dnsrecords
     * @return PdnsDomain
     */
    public function addDnsrecord(\ACS\ACSPanelBundle\Entity\PdnsRecord $dnsrecords)
    {
        $this->dnsrecords[] = $dnsrecords;
    
        return $this;
    }

    /**
     * Remove dnsrecords
     *
     * @param \ACS\ACSPanelBundle\Entity\PdnsRecord $dnsrecords
     */
    public function removeDnsrecord(\ACS\ACSPanelBundle\Entity\PdnsRecord $dnsrecords)
    {
        $this->dnsrecords->removeElement($dnsrecords);
    }

    /**
     * Get dnsrecords
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDnsrecords()
    {
        return $this->dnsrecords;
    }
    /**
     * @var \ACS\ACSPanelBundle\Entity\Domain
     */
    private $domain;


    /**
     * Set domain
     *
     * @param \ACS\ACSPanelBundle\Entity\Domain $domain
     * @return PdnsDomain
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