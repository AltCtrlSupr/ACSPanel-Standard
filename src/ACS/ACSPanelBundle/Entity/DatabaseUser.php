<?php


namespace ACS\ACSPanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use ACS\ACSPanelBundle\Model\Entity\AclEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DatabaseUser
 */
class DatabaseUser implements AclEntity
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
     * @var \ACS\ACSPanelUsersBundle\Entity\User
     */
    private $user;

    /**
     * @var \ACS\ACSPanelBundle\Entity\DB
     *
     * @Assert\NotBlank()
     */
    private $db;

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
     * Set user
     *
     * @param \ACS\ACSPanelUsersBundle\Entity\User $user
     * @return DatabaseUser
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

    public function __toString()
    {
        return $this->username;
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
        return $this->getDb()->getOwners();
    }
}
