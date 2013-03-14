<?php


namespace ACS\ACSPanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DB
 */
class DB
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
     * @var \ACS\ACSPanelBundle\Entity\FosUser
     */
    private $user;

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
     * @return DB
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
     * @return DB
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
     * @return DB
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
     * @param \ACS\ACSPanelBundle\Entity\DatabaseUser $databaseUsers
     * @return DB
     */
    public function addDatabaseUser(\ACS\ACSPanelBundle\Entity\DatabaseUser $databaseUsers)
    {
        $this->database_users[] = $databaseUsers;

        return $this;
    }

    /**
     * Remove database_users
     *
     * @param \ACS\ACSPanelBundle\Entity\DatabaseUser $databaseUsers
     */
    public function removeDatabaseUser(\ACS\ACSPanelBundle\Entity\DatabaseUser $databaseUsers)
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
     * @return DB
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
     * @ORM\PrePersist
     */
    public function createDatabase()
    {
		global $kernel;

		if ('AppCache' == get_class($kernel)) {
			$kernel = $kernel->getKernel();
		}

		$em = $kernel->getContainer()->get('doctrine.dbal.admin_con_connection');

        $sql = "CREATE DATABASE ".$this->getName();
        $em->executeQuery($sql);

		return $this;

    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
	    $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDatabase()
    {
        // Add your code here
    }


    /**
     * Set user
     *
     * @param \ACS\ACSPanelBundle\Entity\FosUser $user
     * @return DB
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

    /**
     * @ORM\PreRemove
     */
    public function removeDatabase()
    {
 		global $kernel;

		if ('AppCache' == get_class($kernel)) {
			$kernel = $kernel->getKernel();
		}

		$em = $kernel->getContainer()->get('doctrine.dbal.admin_con_connection');

        $sql = "DROP DATABASE ".$this->getName();
        $em->executeQuery($sql);

		return $this;
    }
}