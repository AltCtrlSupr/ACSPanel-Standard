<?php
/**
 * ServerRepository
 *
 * @author genar
 */
namespace ACS\ACSPanelBundle\Model;

use ACS\ACSPanelUsersBundle\Entity\User;

use ACS\ACSPanelUsersBundle\Doctrine\AclEntityRepository;

class ServerRepository extends AclEntityRepository
{
    public function getUserViewable($user)
    {
		$entities_raw = $this->_em->createQuery('SELECT s FROM ACS\ACSPanelBundle\Entity\Server s');
		$entities = $this->getAclFilter()->apply($entities_raw, ['VIEW'], $user, 's')->getResult();

        return $entities;
    }
}
