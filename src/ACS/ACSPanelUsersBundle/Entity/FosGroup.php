<?php
namespace ACS\ACSPanelUsersBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository as EM;
use ACS\ACSPanelBundle\Model\Entity\AclEntity;

/**
 * ACS\ACSPanelUsersBundle\Entity\FosGroup
 */
class FosGroup extends BaseGroup implements AclEntity
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var array $roles
     */
    protected $roles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;

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
     * Add users
     *
     * @param \ACS\ACSPanelUsersBundle\Entity\User $users
     * @return FosGroup
     */
    public function addUser(\ACS\ACSPanelUsersBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \ACS\ACSPanelUsersBundle\Entity\User $users
     */
    public function removeUser(\ACS\ACSPanelUsersBundle\Entity\User $users)
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

    public function getOwners()
    {
        return 'admins';
    }
}
