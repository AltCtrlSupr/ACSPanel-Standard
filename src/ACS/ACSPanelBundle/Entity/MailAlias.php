<?php


namespace ACS\ACSPanelBundle\Entity;

use Monolog\Logger;
use Doctrine\ORM\Mapping as ORM;

use ACS\ACSPanelBundle\Model\Entity\AclEntity;

/**
 * ACS\ACSPanelBundle\Entity\MailAlias
 */
class MailAlias implements AclEntity
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $address
     */
    private $address;

    /**
     * @var string $goto
     */
    private $goto;

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
     * @var \ACS\ACSPanelBundle\Entity\MailDomain
     */
    private $mail_domain;

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

    public function __toString()
    {
        return $this->getAddress() . ' to ' . $this->getGoto();
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
     * Set address
     *
     * @param string $address
     * @return MailAlias
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set goto
     *
     * @param string $goto
     * @return MailAlias
     */
    public function setGoto($goto)
    {
        $this->goto = $goto;

        return $this;
    }

    /**
     * Get goto
     *
     * @return string
     */
    public function getGoto()
    {
        return $this->goto;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return MailAlias
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
     * @return MailAlias
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
     * @return MailAlias
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return MailAlias
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
     * @return MailAlias
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
     * @return MailAlias
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
     * Set mail_domain
     *
     * @param \ACS\ACSPanelBundle\Entity\MailDomain $mailDomain
     * @return MailAlias
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
     * Check if user has privileges to see this entity
     */
    public function userCanSee($tokenStorage, $auth)
    {
        if($security->isGranted('ROLE_SUPER_ADMIN'))
            return true;

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
