<?php


namespace ACS\ACSPanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Domain
 */
class Domain
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;


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
     * Set domain
     *
     * @param string $domain
     * @return Domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Domain
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
     * @return Domain
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
    public function setUserValue()
    {
		  if(!$this->getUser())
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
     * @var boolean
     */
    private $is_httpd_alias;

    /**
     * @var boolean
     */
    private $is_dns_alias;

    /**
     * @var boolean
     */
    private $is_mail_alias;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $child_domains;

    /**
     * @var \ACS\ACSPanelBundle\Entity\FosUser
     */
    private $user;

    /**
     * @var \ACS\ACSPanelBundle\Entity\Domain
     */
    private $parent_domain;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->child_domains = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Domain
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
     * Set is_httpd_alias
     *
     * @param boolean $isHttpdAlias
     * @return Domain
     */
    public function setIsHttpdAlias($isHttpdAlias)
    {
        $this->is_httpd_alias = $isHttpdAlias;

        return $this;
    }

    /**
     * Get is_httpd_alias
     *
     * @return boolean
     */
    public function getIsHttpdAlias()
    {
        return $this->is_httpd_alias;
    }

    /**
     * Set is_dns_alias
     *
     * @param boolean $isDnsAlias
     * @return Domain
     */
    public function setIsDnsAlias($isDnsAlias)
    {
        $this->is_dns_alias = $isDnsAlias;

        return $this;
    }

    /**
     * Get is_dns_alias
     *
     * @return boolean
     */
    public function getIsDnsAlias()
    {
        return $this->is_dns_alias;
    }

    /**
     * Set is_mail_alias
     *
     * @param boolean $isMailAlias
     * @return Domain
     */
    public function setIsMailAlias($isMailAlias)
    {
        $this->is_mail_alias = $isMailAlias;

        return $this;
    }

    /**
     * Get is_mail_alias
     *
     * @return boolean
     */
    public function getIsMailAlias()
    {
        return $this->is_mail_alias;
    }

    /**
     * Add child_domains
     *
     * @param \ACS\ACSPanelBundle\Entity\Domain $childDomains
     * @return Domain
     */
    public function addChildDomain(\ACS\ACSPanelBundle\Entity\Domain $childDomains)
    {
        $this->child_domains[] = $childDomains;

        return $this;
    }

    /**
     * Remove child_domains
     *
     * @param \ACS\ACSPanelBundle\Entity\Domain $childDomains
     */
    public function removeChildDomain(\ACS\ACSPanelBundle\Entity\Domain $childDomains)
    {
        $this->child_domains->removeElement($childDomains);
    }

    /**
     * Get child_domains
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildDomains()
    {
        return $this->child_domains;
    }

    /**
     * Set user
     *
     * @param \ACS\ACSPanelBundle\Entity\FosUser $user
     * @return Domain
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
     * Set parent_domain
     *
     * @param \ACS\ACSPanelBundle\Entity\Domain $parentDomain
     * @return Domain
     */
    public function setParentDomain(\ACS\ACSPanelBundle\Entity\Domain $parentDomain = null)
    {
        $this->parent_domain = $parentDomain;

        return $this;
    }

    /**
     * Get parent_domain
     *
     * @return \ACS\ACSPanelBundle\Entity\Domain
     */
    public function getParentDomain()
    {
        return $this->parent_domain;
    }

    public function getName()
    {
        return $this->getDomain();
    }

    public function __toString()
    {
        return $this->getDomain();
    }
    /**
     * @var \ACS\ACSPanelBundle\Entity\HttpdHost
     */
    private $httpd_host;


    /**
     * Set httpd_host
     *
     * @param \ACS\ACSPanelBundle\Entity\HttpdHost $httpdHost
     * @return Domain
     */
    public function setHttpdHost(\ACS\ACSPanelBundle\Entity\HttpdHost $httpdHost = null)
    {
        $this->httpd_host = $httpdHost;

        return $this;
    }

    /**
     * Get httpd_host
     *
     * @return \ACS\ACSPanelBundle\Entity\HttpdHost
     */
    public function getHttpdHost()
    {
        return $this->httpd_host;
    }
}
