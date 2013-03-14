<?php

namespace ACS\ACSPanelBundle\Entity;

use FOS\UserBundle\Entity\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository as EM;

/**
 * ACS\ACSPanelBundle\Entity\FosGroup
 */
class FosGroup extends BaseGroup
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var array $roles
     */
    protected $roles;

	public function __construct(){
		parent::__construct('',array());
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
	 * Returns the name of the group
	 * @return type
	 */
    public function __toString(){
        return $this->name;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;


    /**
     * Add users
     *
     * @param \ACS\ACSPanelBundle\Entity\FosUser $users
     * @return FosGroup
     */
    public function addUser(\ACS\ACSPanelBundle\Entity\FosUser $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \ACS\ACSPanelBundle\Entity\FosUser $users
     */
    public function removeUser(\ACS\ACSPanelBundle\Entity\FosUser $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}