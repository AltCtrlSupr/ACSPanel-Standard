<?php


namespace ACS\ACSPanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DnsDomain
 */
class DnsDomain
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $master;

    /**
     * @var integer
     */
    private $lastCheck;

    /**
     * @var string
     */
    private $type;

    /**
     * @var integer
     */
    private $notifiedSerial;

    /**
     * @var string
     */
    private $account;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \ACS\ACSPanelBundle\Entity\Domain
     */
    private $domain;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $dnsrecords;

    /**
     * @var \ACS\ACSPanelBundle\Entity\Service
     */
    private $service;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dnsrecords = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return DnsDomain
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
     * @return DnsDomain
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
     * @return DnsDomain
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
     * @return DnsDomain
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
     * @return DnsDomain
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
     * @return DnsDomain
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return DnsDomain
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
     * @return DnsDomain
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
     * @return DnsDomain
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
     * Set domain
     *
     * @param \ACS\ACSPanelBundle\Entity\Domain $domain
     * @return DnsDomain
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

    /**
     * Add dnsrecords
     *
     * @param \ACS\ACSPanelBundle\Entity\DnsRecord $dnsrecords
     * @return DnsDomain
     */
    public function addDnsrecord(\ACS\ACSPanelBundle\Entity\DnsRecord $dnsrecords)
    {
        $this->dnsrecords[] = $dnsrecords;

        return $this;
    }

    /**
     * Remove dnsrecords
     *
     * @param \ACS\ACSPanelBundle\Entity\DnsRecord $dnsrecords
     */
    public function removeDnsrecord(\ACS\ACSPanelBundle\Entity\DnsRecord $dnsrecords)
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
     * Set service
     *
     * @param \ACS\ACSPanelBundle\Entity\Service $service
     * @return DnsDomain
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

	 public function __toString()
	 {
				return $this->domain->__toString();
	 }

    public function userCanSee($security)
    {
        if($security->isGranted('ROLE_SUPER_ADMIN'))
            return true;

        $user_to_check = $this->getDomain()->getUser();
        $user = $security->getToken()->getUser();

        if($security->isGranted('ROLE_USER')){
            if($user == $user_to_check)
                return true;
        }

        if($security->isGranted('ROLE_RESELLER')){
            $users = $user->getIdChildIds();
            foreach($users as $childuser){
                if($childuser == $user_to_check)
                    return true;
            }
        }

        return false;

    }

}
