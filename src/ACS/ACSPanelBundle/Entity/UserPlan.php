<?php


namespace ACS\ACSPanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPlan
 */
class UserPlan
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
     * @var \ACS\ACSPanelBundle\Entity\FosUser
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
     * @param \ACS\ACSPanelBundle\Entity\FosUser $puser
     * @return UserPlan
     */
    public function setPuser(\ACS\ACSPanelBundle\Entity\FosUser $puser = null)
    {
        $this->puser = $puser;

        return $this;
    }

    /**
     * Get puser
     *
     * @return \ACS\ACSPanelBundle\Entity\FosUser
     */
    public function getPuser()
    {
        return $this->puser;
    }
}