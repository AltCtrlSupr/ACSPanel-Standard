<?php
namespace ACS\ACSPanelBundle\Entity;

use ACS\ACSPanelSettingsBundle\Entity\ConfigSetting as BaseSetting;

use Doctrine\ORM\Mapping as ORM;

/**
 * PanelSetting
 * @todo Move to settingsbundle
 */
class PanelSetting extends BaseSetting
{
    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \ACS\ACSPanelBundle\Entity\FosUser
     */
    private $user;

    /**
     * @var \ACS\ACSPanelBundle\Entity\Server
     */
    private $server;

    /**
     * @var \ACS\ACSPanelBundle\Entity\Service
     */
    private $service;

    /**
     * @var string
     */
    private $focus;

    /**
     * @var string
     */
    private $context;

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PanelSetting
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
     * @return PanelSetting
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
     * @return PanelSetting
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
     * Set server
     *
     * @param \ACS\ACSPanelBundle\Entity\Server $server
     * @return PanelSetting
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
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }

        $service = $kernel->getContainer()->get('security.context');

        if($service->getToken())
            $user = $service->getToken()->getUser();
        else
            $user = null;
        return $this->setUser($user);
    }

    /**
     * Set service
     *
     * @param \ACS\ACSPanelBundle\Entity\Service $service
     * @return PanelSetting
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
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
    }
}
