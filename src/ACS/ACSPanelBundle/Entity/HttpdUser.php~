<?php


namespace ACS\ACSPanelBundle\Entity;

use Monolog\Logger;
use Doctrine\ORM\Mapping as ORM;

/**
 * ACS\ACSPanelBundle\Entity\HttpdUser
 */
class HttpdUser
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var boolean $host
     */
    private $host;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $password
     */
    private $password;

    /**
     * @var string $groups
     */
    private $groups;


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
     * Set host
     *
     * @param boolean $host
     * @return HttpdUser
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host
     *
     * @return boolean
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return HttpdUser
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
     * Set password
     *
     * @param string $password
     * @return HttpdUser
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
     * Set groups
     *
     * @param string $groups
     * @return HttpdUser
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Get groups
     *
     * @return string
     */
    public function getGroups()
    {
        return $this->groups;
    }
    /**
     * @var string $protected_dir
     */
    private $protected_dir;

    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;

    /**
     * @var ACS\ACSPanelBundle\Entity\HttpdHost
     */
    private $httpd_host;


    /**
     * Set protected_dir
     *
     * @param string $protectedDir
     * @return HttpdUser
     */
    public function setProtectedDir($protectedDir)
    {
        $this->protected_dir = $protectedDir;

        return $this;
    }

    /**
     * Get protected_dir
     *
     * @return string
     */
    public function getProtectedDir()
    {
        return $this->protected_dir;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return HttpdUser
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
     * @return HttpdUser
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
     * @param ACS\ACSPanelBundle\Entity\HttpdHost $httpdHost
     * @return HttpdUser
     */
    public function setHttpdHost(\ACS\ACSPanelBundle\Entity\HttpdHost $httpdHost = null)
    {
        $this->httpd_host = $httpdHost;

        return $this;
    }

    /**
     * Get httpd_host
     *
     * @return ACS\ACSPanelBundle\Entity\HttpdHost
     */
    public function getHttpdHost()
    {
        return $this->httpd_host;
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
     * @var boolean
     */
    private $enabled;


    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return HttpdUser
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
}