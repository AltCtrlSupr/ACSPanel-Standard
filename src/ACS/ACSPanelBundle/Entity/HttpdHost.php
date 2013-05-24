<?php

namespace ACS\ACSPanelBundle\Entity;

use Monolog\Logger;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ACS\ACSPanelBundle\Entity\HttpdHost
 *
 *
 */
class HttpdHost
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var boolean $enabled
     */
    private $enabled;

    /**
     * @var boolean $webdav
     */
    private $webdav;

    /**
     * @var boolean $ftp
     */
    private $ftp;

    /**
     * @var boolean $cgi
     */
    private $cgi;

    /**
     * @var boolean $ssi
     */
    private $ssi;

    /**
     * @var boolean $php
     */
    private $php;

    /**
     * @var string $configuration
     */
    private $configuration;

    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;

    /**
     * @var ACS\ACSPanelBundle\Entity\FosUser
     */
    private $user;

    /**
     * @var \ACS\ACSPanelBundle\Entity\Service
     */
    private $service;

    /**
     * @var \ACS\ACSPanelBundle\Entity\Domain
     */
    private $domain;

    /**
     * @var collection $aliases
     */
    private $aliases;


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
     * @return HttpdHost
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return HttpdHost
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
     * Set webdav
     *
     * @param boolean $webdav
     * @return HttpdHost
     */
    public function setWebdav($webdav)
    {
        $this->webdav = $webdav;

        return $this;
    }

    /**
     * Get webdav
     *
     * @return boolean
     */
    public function getWebdav()
    {
        return $this->webdav;
    }

    /**
     * Set ftp
     *
     * @param boolean $ftp
     * @return HttpdHost
     */
    public function setFtp($ftp)
    {
        $this->ftp = $ftp;

        return $this;
    }

    /**
     * Get ftp
     *
     * @return boolean
     */
    public function getFtp()
    {
        return $this->ftp;
    }

    /**
     * Set cgi
     *
     * @param boolean $cgi
     * @return HttpdHost
     */
    public function setCgi($cgi)
    {
        $this->cgi = $cgi;

        return $this;
    }

    /**
     * Get cgi
     *
     * @return boolean
     */
    public function getCgi()
    {
        return $this->cgi;
    }

    /**
     * Set ssi
     *
     * @param boolean $ssi
     * @return HttpdHost
     */
    public function setSsi($ssi)
    {
        $this->ssi = $ssi;

        return $this;
    }

    /**
     * Get ssi
     *
     * @return boolean
     */
    public function getSsi()
    {
        return $this->ssi;
    }

    /**
     * Set php
     *
     * @param boolean $php
     * @return HttpdHost
     */
    public function setPhp($php)
    {
        $this->php = $php;

        return $this;
    }

    /**
     * Get php
     *
     * @return boolean
     */
    public function getPhp()
    {
        return $this->php;
    }

    /**
     * Set configuration
     *
     * @param string $configuration
     * @return HttpdHost
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * Get configuration
     *
     * @return string
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return HttpdHost
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
     * @return HttpdHost
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
     * @param ACS\ACSPanelBundle\Entity\FosUser $user
     * @return HttpdHost
     */
    public function setUser(\ACS\ACSPanelBundle\Entity\FosUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return ACS\ACSPanelBundle\Entity\FosUser
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

    public function __toString()
    {
        return $this->getName().' ('.$this->getDomain()->getDomain().')';
    }

    /**
     * Set service
     *
     * @param \ACS\ACSPanelBundle\Entity\Service $service
     * @return HttpdHost
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
     * Set domain
     *
     * @param \ACS\ACSPanelBundle\Entity\Domain $domain
     * @return HttpdHost
     */
    public function setDomain(\ACS\ACSPanelBundle\Entity\Domain $domain = null)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return \ACS\ACSPanelBundle\Entity\Domain
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Returns the aliases domains assigned to this hosting
     *
     * @return Collection \ACS\ACSPanelBundle\Entity\Domain
     */
    public function getAliases()
    {
        $aliases = $this->getDomain()->getChildDomains();
        //echo get_class($aliases);
        foreach ($aliases as $alias) {
            if (!$alias->getEnabled() || !$alias->getIsHttpdAlias()) {
                $aliases->removeElement($alias);
            }
        }
        return $aliases;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add user
     *
     * @param \ACS\ACSPanelBundle\Entity\FosUser $user
     * @return HttpdHost
     */
    public function addUser(\ACS\ACSPanelBundle\Entity\FosUser $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \ACS\ACSPanelBundle\Entity\FosUser $user
     */
    public function removeUser(\ACS\ACSPanelBundle\Entity\FosUser $user)
    {
        $this->user->removeElement($user);
    }
}
