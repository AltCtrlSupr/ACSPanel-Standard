<?php

namespace ACS\ACSPanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use ACS\ACSPanelBundle\Model\Entity\AclEntity;

use JMS\Serializer\Annotation as JMS;

/**
 * Service
 *
 * @JMS\ExclusionPolicy("all")
 */
class Service implements AclEntity
{
    /**
     * @var integer
     * @JMS\Expose()
     */
    private $id;

    /**
     * @var string
     * @JMS\Expose()
     */
    private $name;

    /**
     * @var string
     * @JMS\Expose()
     */
    private $ip;

    /**
     * @var \DateTime
     * @JMS\Expose()
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @JMS\Expose()
     */
    private $updatedAt;

    /**
     * @var \ACS\ACSPanelUsersBundle\Entity\User
     */
    private $user;

    /**
     * @var \ACS\ACSPanelBundle\Entity\Server
     */
    private $server;

    /**
     * @var \ACS\ACSPanelBundle\Entity\ServiceType
     */
    private $type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $databases;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $dns_domains;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $ftp_users;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $httpd_hosts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $mail_domains;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $proxyed_httpd_hosts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $settings;

    /**
     * @var boolean
     */
    private $enabled;

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
     * @return Service
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
     * Set ip
     *
     * @param string $ip
     * @return Service
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Service
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
     * @return Service
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
     * @return Service
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
     * Set server
     *
     * @param \ACS\ACSPanelBundle\Entity\Server $server
     * @return Service
     */
    public function setServer(\ACS\ACSPanelBundle\Entity\Server $server = null)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Get server
     *
     * @return \ACS\ACSPanelBundle\Entity\Server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set type
     *
     * @param \ACS\ACSPanelBundle\Entity\ServiceType $type
     * @return Service
     */
    public function setType(\ACS\ACSPanelBundle\Entity\ServiceType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \ACS\ACSPanelBundle\Entity\ServiceType
     */
    public function getType()
    {
        return $this->type;
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

	public function __toString()
	{
		return $this->name;
	}
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->databases = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dns_domains = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ftp_users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->httpd_hosts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mail_domains = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add databases
     *
     * @param \ACS\ACSPanelBundle\Entity\DB $databases
     * @return Service
     */
    public function addDatabase(\ACS\ACSPanelBundle\Entity\DB $databases)
    {
        $this->databases[] = $databases;

        return $this;
    }

    /**
     * Remove databases
     *
     * @param \ACS\ACSPanelBundle\Entity\DB $databases
     */
    public function removeDatabase(\ACS\ACSPanelBundle\Entity\DB $databases)
    {
        $this->databases->removeElement($databases);
    }

    /**
     * Get databases
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDatabases()
    {
        return $this->databases;
    }

    /**
     * Add dns_domains
     *
     * @param \ACS\ACSPanelBundle\Entity\DnsDomain $dnsDomains
     * @return Service
     */
    public function addDnsDomain(\ACS\ACSPanelBundle\Entity\DnsDomain $dnsDomains)
    {
        $this->dns_domains[] = $dnsDomains;

        return $this;
    }

    /**
     * Remove dns_domains
     *
     * @param \ACS\ACSPanelBundle\Entity\DnsDomain $dnsDomains
     */
    public function removeDnsDomain(\ACS\ACSPanelBundle\Entity\DnsDomain $dnsDomains)
    {
        $this->dns_domains->removeElement($dnsDomains);
    }

    /**
     * Get dns_domains
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDnsDomains()
    {
        return $this->dns_domains;
    }

    /**
     * Add ftp_users
     *
     * @param \ACS\ACSPanelBundle\Entity\FtpdUser $ftpUsers
     * @return Service
     */
    public function addFtpUser(\ACS\ACSPanelBundle\Entity\FtpdUser $ftpUsers)
    {
        $this->ftp_users[] = $ftpUsers;

        return $this;
    }

    /**
     * Remove ftp_users
     *
     * @param \ACS\ACSPanelBundle\Entity\FtpdUser $ftpUsers
     */
    public function removeFtpUser(\ACS\ACSPanelBundle\Entity\FtpdUser $ftpUsers)
    {
        $this->ftp_users->removeElement($ftpUsers);
    }

    /**
     * Get ftp_users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFtpUsers()
    {
        return $this->ftp_users;
    }

    /**
     * Add httpd_hosts
     *
     * @param \ACS\ACSPanelBundle\Entity\HttpdHost $httpdHosts
     * @return Service
     */
    public function addHttpdHost(\ACS\ACSPanelBundle\Entity\HttpdHost $httpdHosts)
    {
        $this->httpd_hosts[] = $httpdHosts;

        return $this;
    }

    /**
     * Remove httpd_hosts
     *
     * @param \ACS\ACSPanelBundle\Entity\HttpdHost $httpdHosts
     */
    public function removeHttpdHost(\ACS\ACSPanelBundle\Entity\HttpdHost $httpdHosts)
    {
        $this->httpd_hosts->removeElement($httpdHosts);
    }

    /**
     * Get httpd_hosts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHttpdHosts()
    {
        return $this->httpd_hosts;
    }

    /**
     * Add mail_domains
     *
     * @param \ACS\ACSPanelBundle\Entity\MailDomain $mailDomains
     * @return Service
     */
    public function addMailDomain(\ACS\ACSPanelBundle\Entity\MailDomain $mailDomains)
    {
        $this->mail_domains[] = $mailDomains;

        return $this;
    }

    /**
     * Remove mail_domains
     *
     * @param \ACS\ACSPanelBundle\Entity\MailDomain $mailDomains
     */
    public function removeMailDomain(\ACS\ACSPanelBundle\Entity\MailDomain $mailDomains)
    {
        $this->mail_domains->removeElement($mailDomains);
    }

    /**
     * Get mail_domains
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMailDomains()
    {
        return $this->mail_domains;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Service
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
     * Add settings
     *
     * @param \ACS\ACSPanelBundle\Entity\PanelSettings $settings
     * @return Service
     */
    public function addSetting(\ACS\ACSPanelBundle\Entity\PanelSetting $settings)
    {
        $this->settings[] = $settings;

        return $this;
    }

    /**
     * Remove settings
     *
     * @param \ACS\ACSPanelBundle\Entity\PanelSettings $settings
     */
    public function removeSetting(\ACS\ACSPanelBundle\Entity\PanelSetting $settings)
    {
        $this->settings->removeElement($settings);
    }

    /**
     * Get settings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Add proxyed_httpd_hosts
     *
     * @param \ACS\ACSPanelBundle\Entity\HttpdHost $proxyedHttpdHosts
     * @return Service
     */
    public function addProxyedHttpdHost(\ACS\ACSPanelBundle\Entity\HttpdHost $proxyedHttpdHosts)
    {
        $this->proxyed_httpd_hosts[] = $proxyedHttpdHosts;

        return $this;
    }

    /**
     * Remove proxyed_httpd_hosts
     *
     * @param \ACS\ACSPanelBundle\Entity\HttpdHost $proxyedHttpdHosts
     */
    public function removeProxyedHttpdHost(\ACS\ACSPanelBundle\Entity\HttpdHost $proxyedHttpdHosts)
    {
        $this->proxyed_httpd_hosts->removeElement($proxyedHttpdHosts);
    }

    /**
     * Get proxyed_httpd_hosts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProxyedHttpdHosts()
    {
        return $this->proxyed_httpd_hosts;
    }

    /**
     * Check if user has privileges to see this entity
     */
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
