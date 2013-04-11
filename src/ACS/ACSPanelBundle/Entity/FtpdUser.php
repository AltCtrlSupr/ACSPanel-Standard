<?php


namespace ACS\ACSPanelBundle\Entity;

use Monolog\Logger;
use Doctrine\ORM\Mapping as ORM;

/**
 * ACS\ACSPanelBundle\Entity\FtpdUser
 */
class FtpdUser
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $user
     */
    private $user;

    /**
     * @var string $password
     */
    private $password;

    /**
     * @var integer $uid
     */
    private $uid;

    /**
     * @var integer $gid
     */
    private $gid;

    /**
     * @var string $dir
     */
    private $dir;

    /**
     * @var string $userName
     */
    private $userName;

    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;

    /**
     * @var integer
     */
    private $quota;

    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;

    /**
     * @var boolean $enabled
     */
    private $enabled;

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
     * Set user
     *
     * @param string $user
     * @return FtpdUser
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return FtpdUser
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
     * Set uid
     *
     * @param integer $uid
     * @return FtpdUser
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return integer
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set gid
     *
     * @param integer $gid
     * @return FtpdUser
     */
    public function setGid($gid)
    {
        $this->gid = $gid;

        return $this;
    }

    /**
     * Get gid
     *
     * @return integer
     */
    public function getGid()
    {
        return $this->gid;
    }

    /**
     * Set dir
     *
     * @param string $dir
     * @return FtpdUser
     */
    public function setDir($dir)
    {
        $this->dir = $dir;

        return $this;
    }

    /**
     * Get dir
     *
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * Set userName
     *
     * @param string $userName
     * @return FtpdUser
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return FtpdUser
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
     * @return FtpdUser
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return FtpdUser
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
     * @return FtpdUser
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
     * Set quota
     *
     * @param integer $quota
     * @return FtpdUser
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

    public function __toString()
    {
        return $this->getUserName();
    }
}
