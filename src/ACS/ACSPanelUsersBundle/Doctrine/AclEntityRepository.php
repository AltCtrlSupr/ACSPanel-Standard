<?php

namespace ACS\ACSPanelUsersBundle\Doctrine;

use Doctrine\ORM\EntityRepository;

class AclEntityRepository extends EntityRepository
{
    private $acl_filter;

    /**
     * setAclFilter
     *
     * @param mixed $acl_filter
     * @access public
     * @return void
     */
    public function setAclFilter($acl_filter)
    {
        $this->acl_filter = $acl_filter;
    }

	/**
	 * getAclFilter
	 *
	 * @access public
	 * @return void
	 */
	public function getAclFilter()
	{
        return $this->acl_filter;
	}
}
