<?php

namespace ACS\ACSPanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MailLogrcvd
 */
class MailLogrcvd
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $sender;

    /**
     * @var string
     */
    private $rcpt;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \ACS\ACSPanelBundle\Entity\FosUser
     */
    private $user;

    /**
     * @var \ACS\ACSPanelBundle\Entity\MailDomain
     */
    private $domain;


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
     * Set sender
     *
     * @param string $sender
     * @return MailLogrcvd
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    
        return $this;
    }

    /**
     * Get sender
     *
     * @return string 
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set rcpt
     *
     * @param string $rcpt
     * @return MailLogrcvd
     */
    public function setRcpt($rcpt)
    {
        $this->rcpt = $rcpt;
    
        return $this;
    }

    /**
     * Get rcpt
     *
     * @return string 
     */
    public function getRcpt()
    {
        return $this->rcpt;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return MailLogrcvd
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
     * @return MailLogrcvd
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
     * Set user
     *
     * @param \ACS\ACSPanelBundle\Entity\FosUser $user
     * @return MailLogrcvd
     */
    public function setUser(\ACS\ACSPanelBundle\Entity\FosUser $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \ACS\ACSPanelBundle\Entity\FosUser 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set domain
     *
     * @param \ACS\ACSPanelBundle\Entity\MailDomain $domain
     * @return MailLogrcvd
     */
    public function setDomain(\ACS\ACSPanelBundle\Entity\MailDomain $domain = null)
    {
        $this->domain = $domain;
    
        return $this;
    }

    /**
     * Get domain
     *
     * @return \ACS\ACSPanelBundle\Entity\MailDomain 
     */
    public function getDomain()
    {
        return $this->domain;
    }
    /**
     * @var \ACS\ACSPanelBundle\Entity\MailDomain
     */
    private $mail_domain;


    /**
     * Set mail_domain
     *
     * @param \ACS\ACSPanelBundle\Entity\MailDomain $mailDomain
     * @return MailLogrcvd
     */
    public function setMailDomain(\ACS\ACSPanelBundle\Entity\MailDomain $mailDomain = null)
    {
        $this->mail_domain = $mailDomain;
    
        return $this;
    }

    /**
     * Get mail_domain
     *
     * @return \ACS\ACSPanelBundle\Entity\MailDomain 
     */
    public function getMailDomain()
    {
        return $this->mail_domain;
    }
}