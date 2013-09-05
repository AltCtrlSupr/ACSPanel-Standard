<?php


namespace ACS\ACSPanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DnsRecord
 */
class DnsRecord
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
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $content;

    /**
     * @var integer
     */
    private $ttl;

    /**
     * @var integer
     */
    private $prio;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \ACS\ACSPanelBundle\Entity\DnsDomain
     */
    private $domain;

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
     * Set name
     *
     * @param string $name
     * @return DnsRecord
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
     * Set type
     *
     * @param string $type
     * @return DnsRecord
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return DnsRecord
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set ttl
     *
     * @param integer $ttl
     * @return DnsRecord
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * Get ttl
     *
     * @return integer
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * Set prio
     *
     * @param integer $prio
     * @return DnsRecord
     */
    public function setPrio($prio)
    {
        $this->prio = $prio;

        return $this;
    }

    /**
     * Get prio
     *
     * @return integer
     */
    public function getPrio()
    {
        return $this->prio;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return DnsRecord
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
     * @return DnsRecord
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
     * Set domain
     *
     * @param \ACS\ACSPanelBundle\Entity\DnsDomain $domain
     * @return DnsRecord
     * @deprecated
     */
    public function setDomain(\ACS\ACSPanelBundle\Entity\DnsDomain $domain = null)
    {
        return $this->setDnsDomain($domain);
    }

    /**
     * Get domain
     *
     * @return \ACS\ACSPanelBundle\Entity\DnsDomain
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set user
     *
     * @param \ACS\ACSPanelBundle\Entity\FosUser $user
     * @return DnsRecord
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
		return $this->name;
	}
    /**
     * @var \ACS\ACSPanelBundle\Entity\DnsDomain
     */
    private $dns_domain;


    /**
     * Set dns_domain
     *
     * @param \ACS\ACSPanelBundle\Entity\DnsDomain $dnsDomain
     * @return DnsRecord
     */
    public function setDnsDomain(\ACS\ACSPanelBundle\Entity\DnsDomain $dnsDomain = null)
    {
        $this->dns_domain = $dnsDomain;

        return $this;
    }

    /**
     * Get dns_domain
     *
     * @return \ACS\ACSPanelBundle\Entity\DnsDomain
     */
    public function getDnsDomain()
    {
        return $this->dns_domain;
    }
}
