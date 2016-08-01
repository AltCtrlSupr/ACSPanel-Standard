<?php

namespace ACS\ACSPanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use ACS\ACSPanelBundle\Model\Entity\AclEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ACS\ACSPanelBundle\Entity\DB
 */
class DB implements AclEntity
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
     *
     * @Assert\NotBlank()
     */
    private $service;

    /**
     * @var \ACS\ACSPanelUsersBundle\Entity\User
     */
    private $user;

    /**
     * @var string
     */
    private $description;

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
     * Set user
     *
     * @param \ACS\ACSPanelUsersBundle\Entity\User $user
     * @return DB
     */
    public function setUser(\ACS\ACSPanelUsersBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ACS\ACSPanelUsersBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return DB
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

    public function userCanSee($tokenStorage, $auth)
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
                if($childuser == $user_to_check->getId())
                    return true;
            }
        }

        return false;

    }

    public function getOwners()
    {
        return $this->getUser();
    }
}
