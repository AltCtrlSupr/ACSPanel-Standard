<?php

namespace ACS\ACSPanelUsersBundle\Doctrine;

use Doctrine\ORM\EntityRepository;

class AclEntityRepository extends EntityRepository
{
    private $acl_filter;

    public function setAclFilter($acl_filter)
    {
        $this->acl_filter = $acl_filter;
    }

	public function getAclFilter()
	{
        return $this->acl_filter;
	}
}
