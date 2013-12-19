<?php

namespace ACS\ACSPanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MailWBList
 */
class MailWBList
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
     * @var string
     */
    private $reject;

    /**
     * @var boolean
     */
    private $blacklisted;

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
     * @var \ACS\ACSPanelBundle\Entity\FosUser
     */
    private $user;


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
     * @return MailWBList
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
     * @return MailWBList
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
     * Set reject
     *
     * @param string $reject
     * @return MailWBList
     */
    public function setReject($reject)
    {
        $this->reject = $reject;

        return $this;
    }

    /**
     * Get reject
     *
     * @return string
     */
    public function getReject()
    {
        return $this->reject;
    }

    /**
     * Set blacklisted
     *
     * @param boolean $blacklisted
     * @return MailWBList
     */
    public function setBlacklisted($blacklisted)
    {
        $this->blacklisted = $blacklisted;

        return $this;
    }

    /**
     * Get blacklisted
     *
     * @return boolean
     */
    public function getBlacklisted()
    {
        return $this->blacklisted;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return MailWBList
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
     * @return MailWBList
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
     * @return MailWBList
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
     * @param \ACS\ACSPanelBundle\Entity\FosUser $user
     * @return MailWBList
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
     * @ORM\PrePersist
     */
    public function setUserValue()
    {
        if($this->getUser())
            return;

      global $kernel;

      if ('AppCache' == get_class($kernel)) {
         $kernel = $kernel->getKernel();
      }

      $service = $kernel->getContainer()->get('security.context');

        // Add your code here
      $user = $service->getToken()->getUser();
      return $this->setUser($user);
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
		  $this->updatedAt = new \DateTime();
    }

    /**
     * Check if user has privileges to see this entity
     */
    public function userCanSee($security)
    {
        if($security->isGranted('ROLE_SUPER_ADMIN'))
            return true;

        $user_to_check = $this->getUser();
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
