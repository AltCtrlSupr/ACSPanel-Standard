<?php


namespace ACS\ACSPanelBundle\Entity;

use Monolog\Logger;
use Doctrine\ORM\Mapping as ORM;

/**
 * ACS\ACSPanelBundle\Entity\Plan
 */
class Plan
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $planName
     */
    private $planName;

    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $uplans;

    /**
     * @var integer
     */
    private $maxPanelReseller;

    /**
     * @var integer
     */
    private $maxPanelUser;

    /**
     * @var integer
     */
    private $maxHttpdHost;

    /**
     * @var integer
     */
    private $maxHttpdAlias;

    /**
     * @var integer
     */
    private $maxHttpdUser;

    /**
     * @var integer
     */
    private $maxDnsDomain;

    /**
     * @var integer
     */
    private $maxMailDomain;

    /**
     * @var integer
     */
    private $maxMailMailbox;

    /**
     * @var integer
     */
    private $maxMailAlias;

    /**
     * @var integer
     */
    private $maxMailAliasDomain;


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
     * Set planName
     *
     * @param string $planName
     * @return Plan
     */
    public function setPlanName($planName)
    {
        $this->planName = $planName;

        return $this;
    }

    /**
     * Get planName
     *
     * @return string
     */
    public function getPlanName()
    {
        return $this->planName;
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Plan
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
     * @return Plan
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
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->planName;
    }

    /**
     * Set maxPanelReseller
     *
     * @param integer $maxPanelReseller
     * @return Plan
     */
    public function setMaxPanelReseller($maxPanelReseller)
    {
        $this->maxPanelReseller = $maxPanelReseller;

        return $this;
    }

    /**
     * Get maxPanelReseller
     *
     * @return integer
     */
    public function getMaxPanelReseller()
    {
        return $this->maxPanelReseller;
    }

    /**
     * Set maxPanelUser
     *
     * @param integer $maxPanelUser
     * @return Plan
     */
    public function setMaxPanelUser($maxPanelUser)
    {
        $this->maxPanelUser = $maxPanelUser;

        return $this;
    }

    /**
     * Get maxPanelUser
     *
     * @return integer
     */
    public function getMaxPanelUser()
    {
        return $this->maxPanelUser;
    }

    /**
     * Set maxHttpdHost
     *
     * @param integer $maxHttpdHost
     * @return Plan
     */
    public function setMaxHttpdHost($maxHttpdHost)
    {
        $this->maxHttpdHost = $maxHttpdHost;

        return $this;
    }

    /**
     * Get maxHttpdHost
     *
     * @return integer
     */
    public function getMaxHttpdHost()
    {
        return $this->maxHttpdHost;
    }

    /**
     * Set maxHttpdAlias
     *
     * @param integer $maxHttpdAlias
     * @return Plan
     */
    public function setMaxHttpdAlias($maxHttpdAlias)
    {
        $this->maxHttpdAlias = $maxHttpdAlias;

        return $this;
    }

    /**
     * Get maxHttpdAlias
     *
     * @return integer
     */
    public function getMaxHttpdAlias()
    {
        return $this->maxHttpdAlias;
    }

    /**
     * Set maxHttpdUser
     *
     * @param integer $maxHttpdUser
     * @return Plan
     */
    public function setMaxHttpdUser($maxHttpdUser)
    {
        $this->maxHttpdUser = $maxHttpdUser;

        return $this;
    }

    /**
     * Get maxHttpdUser
     *
     * @return integer
     */
    public function getMaxHttpdUser()
    {
        return $this->maxHttpdUser;
    }

    /**
     * Set maxDnsDomain
     *
     * @param integer $maxDnsDomain
     * @return Plan
     */
    public function setMaxDnsDomain($maxDnsDomain)
    {
        $this->maxDnsDomain = $maxDnsDomain;

        return $this;
    }

    /**
     * Get maxDnsDomain
     *
     * @return integer
     */
    public function getMaxDnsDomain()
    {
        return $this->maxDnsDomain;
    }

    /**
     * Set maxMailDomain
     *
     * @param integer $maxMailDomain
     * @return Plan
     */
    public function setMaxMailDomain($maxMailDomain)
    {
        $this->maxMailDomain = $maxMailDomain;

        return $this;
    }

    /**
     * Get maxMailDomain
     *
     * @return integer
     */
    public function getMaxMailDomain()
    {
        return $this->maxMailDomain;
    }

    /**
     * Set maxMailMailbox
     *
     * @param integer $maxMailMailbox
     * @return Plan
     */
    public function setMaxMailMailbox($maxMailMailbox)
    {
        $this->maxMailMailbox = $maxMailMailbox;

        return $this;
    }

    /**
     * Get maxMailMailbox
     *
     * @return integer
     */
    public function getMaxMailMailbox()
    {
        return $this->maxMailMailbox;
    }

    /**
     * Set maxMailAlias
     *
     * @param integer $maxMailAlias
     * @return Plan
     */
    public function setMaxMailAlias($maxMailAlias)
    {
        $this->maxMailAlias = $maxMailAlias;

        return $this;
    }

    /**
     * Get maxMailAlias
     *
     * @return integer
     */
    public function getMaxMailAlias()
    {
        return $this->maxMailAlias;
    }

    /**
     * Set maxMailAliasDomain
     *
     * @param integer $maxMailAliasDomain
     * @return Plan
     */
    public function setMaxMailAliasDomain($maxMailAliasDomain)
    {
        $this->maxMailAliasDomain = $maxMailAliasDomain;

        return $this;
    }

    /**
     * Get maxMailAliasDomain
     *
     * @return integer
     */
    public function getMaxMailAliasDomain()
    {
        return $this->maxMailAliasDomain;
    }
    /**
     * @var integer
     */
    private $maxFtpdUser;


    /**
     * Set maxFtpdUser
     *
     * @param integer $maxFtpdUser
     * @return Plan
     */
    public function setMaxFtpdUser($maxFtpdUser)
    {
        $this->maxFtpdUser = $maxFtpdUser;

        return $this;
    }

    /**
     * Get maxFtpdUser
     *
     * @return integer
     */
    public function getMaxFtpdUser()
    {
        return $this->maxFtpdUser;
    }


    /**
     * Add uplans
     *
     * @param \ACS\ACSPanelBundle\Entity\UserPlan $uplans
     * @return Plan
     */
    public function addUplan(\ACS\ACSPanelBundle\Entity\UserPlan $uplans)
    {
        $this->uplans[] = $uplans;

        return $this;
    }

    /**
     * Remove uplans
     *
     * @param \ACS\ACSPanelBundle\Entity\UserPlan $uplans
     */
    public function removeUplan(\ACS\ACSPanelBundle\Entity\UserPlan $uplans)
    {
        $this->uplans->removeElement($uplans);
    }

    /**
     * Get uplans
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUplans()
    {
        return $this->uplans;
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
     * @var integer
     */
    private $maxDomain;


    /**
     * Set maxDomain
     *
     * @param integer $maxDomain
     * @return Plan
     */
    public function setMaxDomain($maxDomain)
    {
        $this->maxDomain = $maxDomain;

        return $this;
    }

    /**
     * Get maxDomain
     *
     * @return integer
     */
    public function getMaxDomain()
    {
        return $this->maxDomain;
    }
    /**
     * @var integer
     */
    private $maxDb;

    /**
     * @var integer
     */
    private $maxDbUser;


    /**
     * Set maxDb
     *
     * @param integer $maxDb
     * @return Plan
     */
    public function setMaxDb($maxDb)
    {
        $this->maxDb = $maxDb;

        return $this;
    }

    /**
     * Get maxDb
     *
     * @return integer
     */
    public function getMaxDb()
    {
        return $this->maxDb;
    }

    /**
     * Set maxDbUser
     *
     * @param integer $maxDbUser
     * @return Plan
     */
    public function setMaxDbUser($maxDbUser)
    {
        $this->maxDbUser = $maxDbUser;

        return $this;
    }

    /**
     * Get maxDbUser
     *
     * @return integer
     */
    public function getMaxDbUser()
    {
        return $this->maxDbUser;
    }
}
