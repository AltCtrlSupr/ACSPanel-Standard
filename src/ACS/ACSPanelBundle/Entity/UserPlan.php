<?php


namespace ACS\ACSPanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use ACS\ACSPanelBundle\Model\Entity\AclEntity;

/**
 * UserPlan
 */
class UserPlan implements AclEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \ACS\ACSPanelBundle\Entity\Plan
     */
    private $uplans;

    /**
     * @var \ACS\ACSPanelUsersBundle\Entity\User
     */
    private $puser;


    /**
     * Set id
     *
     * @param integer $id
     * @return UserPlan
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
     * Set uplans
     *
     * @param \ACS\ACSPanelBundle\Entity\Plan $uplans
     * @return UserPlan
     */
    public function setUplans(\ACS\ACSPanelBundle\Entity\Plan $uplans = null)
    {
        $this->uplans = $uplans;

        return $this;
    }

    /**
     * Get uplans
     *
     * @return \ACS\ACSPanelBundle\Entity\Plan
     */
    public function getUplans()
    {
        return $this->uplans;
    }

    /**
     * Set puser
     *
     * @param \ACS\ACSPanelBundle\Entity\User $puser
     * @return UserPlan
     */
    public function setPuser(\ACS\ACSPanelUsersBundle\Entity\User $puser = null)
    {
        $this->puser = $puser;

        return $this;
    }

    /**
     * Get puser
     *
     * @return \ACS\ACSPanelBundle\Entity\User
     */
    public function getPuser()
    {
        return $this->puser;
    }

    public function getOwners()
    {
        return $this->getPuser();
    }
}
