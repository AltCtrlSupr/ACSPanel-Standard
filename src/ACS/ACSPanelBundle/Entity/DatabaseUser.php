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
     * @var \ACS\ACSPanelBundle\Entity\DB
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
     * @ORM\PostPersist
     */
    public function createUserInDatabase()
    {


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

    /**
     * @ORM\PreRemove
     */
    public function removeUserInDatabase()
    {
        return;

		global $kernel;

		if ('AppCache' == get_class($kernel)) {
			$kernel = $kernel->getKernel();
		}

        $admin_user = '';
        $admin_password = '';
        $settings = $this->getDb()->getService()->getSettings();
        foreach ($settings as $setting){
            if($setting->getSettingKey() == 'admin_user')
                $admin_user = $setting->getValue();
            if($setting->getSettingKey() == 'admin_password')
                $admin_password = $setting->getValue();
        }
        $server_ip = $this->getDb()->getService()->getIp();

        $config = new \Doctrine\DBAL\Configuration();
        //..
        $connectionParams = array(
            'user' => $admin_user,
            'password' => $admin_password,
            'host' => $server_ip,
            'driver' => 'pdo_mysql',
        );
        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        $sql = "GRANT ALL PRIVILEGES ON `".$this->getDb()."` . * TO '".$this->getUsername()."'@'%'";
        $conn->executeQuery($sql);
        $sql = "DROP USER IF EXISTS '".$this->getUsername()."'@'%'";
        $conn->executeQuery($sql);


    }

    public function __tostring()
    {
        return $this->username;
    }
}
