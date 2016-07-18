<?php
/**
 * HttpdAliasRepository
 *
 * @author genar
 */
namespace ACS\ACSPanelBundle\Model;

use ACS\ACSPanelUsersBundle\Entity\User;

use ACS\ACSPanelUsersBundle\Doctrine\AclEntityRepository;

class PlanRepository extends AclEntityRepository
{
    public function getUserViewable($user)
    {
        $entities_raw = $this->_em->createQuery('SELECT p FROM ACS\ACSPanelBundle\Entity\Plan p');
        $entities = $this->getAclFilter()->apply($entities_raw, ['VIEW'], $user, 'p')->getResult();

        return $entities;
    }
}

