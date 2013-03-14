<?php

namespace ACS\ACSPanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Database
 */
class Database
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
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $database_users;

    /**
     * @var \ACS\ACSPanelBundle\Entity\Service
     */
    private $service;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->database_users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Database
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Database
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
     * @return Database
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
     * Add database_users
     *
     * @param \ACS\ACSPanelBundle\Entity\DatabaseUsers $databaseUsers
     * @return Database
     */
    public function addDatabaseUser(\ACS\ACSPanelBundle\Entity\DatabaseUsers $databaseUsers)
    {
        $this->database_users[] = $databaseUsers;
    
        return $this;
    }

    /**
     * Remove database_users
     *
     * @param \ACS\ACSPanelBundle\Entity\DatabaseUsers $databaseUsers
     */
    public function removeDatabaseUser(\ACS\ACSPanelBundle\Entity\DatabaseUsers $databaseUsers)
    {
        $this->database_users->removeElement($databaseUsers);
    }

    /**
     * Get database_users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDatabaseUsers()
    {
        return $this->database_users;
    }

    /**
     * Set service
     *
     * @param \ACS\ACSPanelBundle\Entity\Service $service
     * @return Database
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
        // Add your code here
    }

    /**
     * @ORM\PrePersist
     */
    public function createDatabase()
    {
        // Add your code here
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        // Add your code here
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDatabase()
    {
        // Add your code here
    }
}