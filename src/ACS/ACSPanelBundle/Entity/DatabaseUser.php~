<?php


namespace ACS\ACSPanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DatabaseUser
 */
class DatabaseUser
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \ACS\ACSPanelBundle\Entity\Database
     */
    private $database;

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
     * Set username
     *
     * @param string $username
     * @return DatabaseUser
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
     * @return DatabaseUser
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return DatabaseUser
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
     * @return DatabaseUser
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
     * Set database
     *
     * @param \ACS\ACSPanelBundle\Entity\Database $database
     * @return DatabaseUser
     */
    public function setDatabase(\ACS\ACSPanelBundle\Entity\Database $database = null)
    {
        $this->database = $database;

        return $this;
    }

    /**
     * Get database
     *
     * @return \ACS\ACSPanelBundle\Entity\Database
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Set user
     *
     * @param \ACS\ACSPanelBundle\Entity\FosUser $user
     * @return DatabaseUser
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
    public function createUserInDatabase()
    {
        // Add your code here
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
    public function updateUserInDatabase()
    {
        // Add your code here
    }
    /**
     * @var \ACS\ACSPanelBundle\Entity\DB
     */
    private $db;


    /**
     * Set db
     *
     * @param \ACS\ACSPanelBundle\Entity\DB $db
     * @return DatabaseUser
     */
    public function setDb(\ACS\ACSPanelBundle\Entity\DB $db = null)
    {
        $this->db = $db;

        return $this;
    }

    /**
     * Get db
     *
     * @return \ACS\ACSPanelBundle\Entity\DB
     */
    public function getDb()
    {
        return $this->db;
    }
}