<?php


namespace ACS\ACSPanelBundle\Entity;

use Monolog\Logger;
use Doctrine\ORM\Mapping as ORM;

/**
 * ACS\ACSPanelBundle\Entity\MailAliasDomain
 */
class MailAliasDomain
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $aliasDomain
     */
    private $aliasDomain;

    /**
     * @var string $targetDomain
     */
    private $targetDomain;

    /**
     * @var \DateTime $created
     */
    private $created;

    /**
     * @var \DateTime $modifiedTime
     */
    private $modifiedTime;

    /**
     * @var boolean $active
     */
    private $active;


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
     * Set aliasDomain
     *
     * @param string $aliasDomain
     * @return MailAliasDomain
     */
    public function setAliasDomain($aliasDomain)
    {
        $this->aliasDomain = $aliasDomain;

        return $this;
    }

    /**
     * Get aliasDomain
     *
     * @return string
     */
    public function getAliasDomain()
    {
        return $this->aliasDomain;
    }

    /**
     * Set targetDomain
     *
     * @param string $targetDomain
     * @return MailAliasDomain
     */
    public function setTargetDomain($targetDomain)
    {
        $this->targetDomain = $targetDomain;

        return $this;
    }

    /**
     * Get targetDomain
     *
     * @return string
     */
    public function getTargetDomain()
    {
        return $this->targetDomain;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return MailAliasDomain
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set modifiedTime
     *
     * @param \DateTime $modifiedTime
     * @return MailAliasDomain
     */
    public function setModifiedTime($modifiedTime)
    {
        $this->modifiedTime = $modifiedTime;

        return $this;
    }

    /**
     * Get modifiedTime
     *
     * @return \DateTime
     */
    public function getModifiedTime()
    {
        return $this->modifiedTime;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return MailAliasDomain
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
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
     * @var ACS\ACSPanelBundle\Entity\MailDomain
     */
    private $domain;


    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return MailAliasDomain
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
     * @return MailAliasDomain
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
     * @return MailAliasDomain
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
     * @param ACS\ACSPanelBundle\Entity\MailDomain $domain
     * @return MailAliasDomain
     */
    public function setDomain(\ACS\ACSPanelBundle\Entity\MailDomain $domain = null)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return ACS\ACSPanelBundle\Entity\MailDomain
     */
    public function getDomain()
    {
        return $this->domain;
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
     * @var \ACS\ACSPanelBundle\Entity\Service
     */
    private $service;


    /**
     * Set service
     *
     * @param \ACS\ACSPanelBundle\Entity\Service $service
     * @return MailAliasDomain
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
     * @var \ACS\ACSPanelBundle\Entity\Domain
     */
    private $mail_domain;

    /**
     * @var \ACS\ACSPanelBundle\Entity\Domain
     */
    private $alias_domain;


    /**
     * Set mail_domain
     *
     * @param \ACS\ACSPanelBundle\Entity\Domain $mailDomain
     * @return MailAliasDomain
     */
    public function setMailDomain(\ACS\ACSPanelBundle\Entity\Domain $mailDomain = null)
    {
        $this->mail_domain = $mailDomain;

        return $this;
    }

    /**
     * Get mail_domain
     *
     * @return \ACS\ACSPanelBundle\Entity\Domain
     */
    public function getMailDomain()
    {
        return $this->mail_domain;
    }
}