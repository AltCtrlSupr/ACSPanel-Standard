<?php


namespace ACS\ACSPanelBundle\Entity;

use Monolog\Logger;
use Doctrine\ORM\Mapping as ORM;

/**
 * ACS\ACSPanelBundle\Entity\MailDomain
 */
class MailDomain
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $domain
     */
    private $domain;

    /**
     * @var string $description
     */
    private $description;

    /**
     * @var integer $maxAliases
     */
    private $maxAliases;

    /**
     * @var integer $maxMailboxes
     */
    private $maxMailboxes;

    /**
     * @var integer $maxQuota
     */
    private $maxQuota;

    /**
     * @var string $transport
     */
    private $transport;

    /**
     * @var boolean $backupmx
     */
    private $backupmx;

    /**
     * @var \DateTime $created
     */
    private $created;

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
     * @var \ACS\ACSPanelBundle\Entity\Service
     */
    private $service;


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
     * Set domain
     *
     * @param string $domain
     * @return MailDomain
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
     * Set description
     *
     * @param string $description
     * @return MailDomain
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set maxAliases
     *
     * @param integer $maxAliases
     * @return MailDomain
     */
    public function setMaxAliases($maxAliases)
    {
        $this->maxAliases = $maxAliases;

        return $this;
    }

    /**
     * Get maxAliases
     *
     * @return integer
     */
    public function getMaxAliases()
    {
        return $this->maxAliases;
    }

    /**
     * Set maxMailboxes
     *
     * @param integer $maxMailboxes
     * @return MailDomain
     */
    public function setMaxMailboxes($maxMailboxes)
    {
        $this->maxMailboxes = $maxMailboxes;

        return $this;
    }

    /**
     * Get maxMailboxes
     *
     * @return integer
     */
    public function getMaxMailboxes()
    {
        return $this->maxMailboxes;
    }

    /**
     * Set maxQuota
     *
     * @param integer $maxQuota
     * @return MailDomain
     */
    public function setMaxQuota($maxQuota)
    {
        $this->maxQuota = $maxQuota;

        return $this;
    }

    /**
     * Get maxQuota
     *
     * @return integer
     */
    public function getMaxQuota()
    {
        return $this->maxQuota;
    }

    /**
     * Set transport
     *
     * @param string $transport
     * @return MailDomain
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Get transport
     *
     * @return string
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Set backupmx
     *
     * @param boolean $backupmx
     * @return MailDomain
     */
    public function setBackupmx($backupmx)
    {
        $this->backupmx = $backupmx;

        return $this;
    }

    /**
     * Get backupmx
     *
     * @return boolean
     */
    public function getBackupmx()
    {
        return $this->backupmx;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return MailDomain
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return MailDomain
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
     * @return MailDomain
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
     * @return MailDomain
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
     * @return MailDomain
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
     * Set service
     *
     * @param \ACS\ACSPanelBundle\Entity\Service $service
     * @return MailDomain
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
        return $this->getDomain()->getDomain();
    }


    /**
     * @ORM\PrePersist
     */
    public function setTransportValue()
    {
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }

        $settings_manager = $kernel->getContainer()->get('acs.setting_manager');
        $mail_domain_transport = $settings_manager->getSystemSetting('mail_domain_transport');
        if($mail_domain_transport){
            $this->setTransport($mail_domain_transport);
        }

        return $this;

    }
}