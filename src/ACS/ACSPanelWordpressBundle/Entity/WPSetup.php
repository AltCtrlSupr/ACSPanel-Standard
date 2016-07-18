<?php

namespace ACS\ACSPanelWordpressBundle\Entity;

use ACS\ACSPanelBundle\Model\Entity\AclEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WPSetup
 */
class WPSetup implements AclEntity
{
    /**
     * @var integer
     */
    private $id;

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
     * @var \ACS\ACSPanelBundle\Entity\HttpdHost
     */
    private $httpd_host;

    /**
     * @var \ACS\ACSPanelBundle\Entity\DatabaseUser
     */
    private $database_user;

    /**
     * @var \ACS\ACSPanelBundle\Entity\User
     */
    private $user;

    public function __toString()
    {
        return $this->getHttpdHost()->__toString();
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return WPSetup
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
     * @return WPSetup
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
     * @return WPSetup
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
     * Set httpd_host
     *
     * @param \ACS\ACSPanelBundle\Entity\HttpdHost $httpdHost
     * @return WPSetup
     */
    public function setHttpdHost(\ACS\ACSPanelBundle\Entity\HttpdHost $httpdHost = null)
    {
        $this->httpd_host = $httpdHost;

        return $this;
    }

    /**
     * Get httpd_host
     *
     * @return \ACS\ACSPanelWordpressBundle\Entity\HttpdHost
     */
    public function getHttpdHost()
    {
        return $this->httpd_host;
    }

    /**
     * Set database_user
     *
     * @param \ACS\ACSPanelBundle\Entity\DatabaseUser $databaseUser
     * @return WPSetup
     */
    public function setDatabaseUser(\ACS\ACSPanelBundle\Entity\DatabaseUser $databaseUser = null)
    {
        $this->database_user = $databaseUser;

        return $this;
    }

    /**
     * Get database_user
     *
     * @return \ACS\ACSPanelWordpressBundle\Entity\DatabaseUser
     */
    public function getDatabaseUser()
    {
        return $this->database_user;
    }

    /**
     * Set user
     *
     * @param \ACS\ACSPanelWordpressBundle\Entity\User $user
     * @return WPSetup
     */
    public function setUser(\ACS\ACSPanelUsersBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ACS\ACSPanelWordpressBundle\Entity\User
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

    public function getOwners()
    {
        return $this->getUser();
    }
}
