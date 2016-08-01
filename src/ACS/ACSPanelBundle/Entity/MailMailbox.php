<?php


namespace ACS\ACSPanelBundle\Entity;

use Monolog\Logger;
use Doctrine\ORM\Mapping as ORM;

use ACS\ACSPanelBundle\Model\Entity\AclEntity;

/**
 * ACS\ACSPanelBundle\Entity\MailMailbox
 */
class MailMailbox implements AclEntity
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $username
     */
    private $username;

    /**
     * @var string $password
     */
    private $password;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $maildir
     */
    private $maildir;

    /**
     * @var integer $quota
     */
    private $quota;

    /**
     * @var integer $usedQuota
     */
    private $usedQuota;

    /**
     * @var string $localPart
     */
    private $localPart;

    /**
     * @var string $domain
     */
    private $domain;

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
     * @var integer $quotaLimit
     */
    private $quotaLimit;

    /**
     * @var integer $bytes
     */
    private $bytes;

    /**
     * @var int $messages
     */
    private $messages;

    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;

    /**
     * @var \ACS\ACSPanelBundle\Entity\MailDomain
     */
    private $mail_domain;

    /**
     * @var boolean
     */
    private $enabled;

    public function __toString()
    {
        return $this->getUsername() . '@' . $this->getMailDomain();
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
     * Set username
     *
     * @param string $username
     * @return MailMailbox
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return MailMailbox
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return MailMailbox
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
     * Set maildir
     *
     * @param string $maildir
     * @return MailMailbox
     */
    public function setMaildir($maildir)
    {
        $this->maildir = $maildir;

        return $this;
    }

    /**
     * Get maildir
     *
     * @return string
     */
    public function getMaildir()
    {
        return $this->maildir;
    }

    /**
     * Set quota
     *
     * @param integer $quota
     * @return MailMailbox
     */
    public function setQuota($quota)
    {
        $this->quota = $quota;

        return $this;
    }

    /**
     * Get quota
     *
     * @return integer
     */
    public function getQuota()
    {
        return $this->quota;
    }

    /**
     * Set usedQuota
     *
     * @param integer $usedQuota
     * @return MailMailbox
     */
    public function setUsedQuota($usedQuota)
    {
        $this->usedQuota = $usedQuota;

        return $this;
    }

    /**
     * Get usedQuota
     *
     * @return integer
     */
    public function getUsedQuota()
    {
        return $this->usedQuota;
    }

    /**
     * Set localPart
     *
     * @param string $localPart
     * @return MailMailbox
     */
    public function setLocalPart($localPart)
    {
        $this->localPart = $localPart;

        return $this;
    }

    /**
     * Get localPart
     *
     * @return string
     */
    public function getLocalPart()
    {
        return $this->localPart;
    }

    /**
     * Set domain
     *
     * @param string $domain
     * @return MailMailbox
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return MailMailbox
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
     * @return MailMailbox
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
     * @return MailMailbox
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
     * Set quotaLimit
     *
     * @param integer $quotaLimit
     * @return MailMailbox
     */
    public function setQuotaLimit($quotaLimit)
    {
        $this->quotaLimit = $quotaLimit;

        return $this;
    }

    /**
     * Get quotaLimit
     *
     * @return integer
     */
    public function getQuotaLimit()
    {
        return $this->quotaLimit;
    }

    /**
     * Set bytes
     *
     * @param integer $bytes
     * @return MailMailbox
     */
    public function setBytes($bytes)
    {
        $this->bytes = $bytes;

        return $this;
    }

    /**
     * Get bytes
     *
     * @return integer
     */
    public function getBytes()
    {
        return $this->bytes;
    }

    /**
     * Set messages
     *
     * @param int $messages
     * @return MailMailbox
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * Get messages
     *
     * @return int
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return MailMailbox
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
     * @return MailMailbox
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return MailMailbox
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
     * Set mail_domain
     *
     * @param \ACS\ACSPanelBundle\Entity\MailDomain $mailDomain
     * @return MailMailbox
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

    /**
     * Returns true if the user can edit this mailbox
     *
     * @return boolean
     */
    public function userCanEdit($user)
    {
        $childs = $user->getIdChildIds();
        foreach($childs as $child)
            if($child == $this->getMailDomain()->getUser()->getId())
                return true;

        return false;
    }

    /**
     * @ORM\PrePersist
     */
    public function setUserValue()
    {
        // Add your code here
    }

    /**
     * Check if user has privileges to see this entity
     */
    public function userCanSee($tokenStorage, $auth)
    {
        if($security->isGranted('ROLE_SUPER_ADMIN'))
            return true;

        // TODO: Check if we will need to show mailboxes to final final users
        $user_to_check = $this->getMailDomain()->getUser();
        $user = $security->getToken()->getUser();

        if($security->isGranted('ROLE_USER')){
            if($user == $user_to_check)
                return true;
        }

        if($security->isGranted('ROLE_RESELLER')){
            $users = $user->getIdChildIds();
            foreach($users as $childuser){
                if($childuser == $user_to_check->getId())
                    return true;
            }
        }

        return false;

    }
    public function getOwners()
    {
        return $this->getMailDomain()->getOwners();
    }
}
