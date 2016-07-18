<?php
/**
 * HttpdAliasRepository
 *
 * @author genar
 */
namespace ACS\ACSPanelBundle\Model;

use ACS\ACSPanelUsersBundle\Entity\User;

use ACS\ACSPanelUsersBundle\Doctrine\AclEntityRepository;

class MailWBListRepository extends AclEntityRepository
{
    public function getUserViewable($user)
    {
		$entities_raw = $this->_em->createQuery('SELECT m FROM ACS\ACSPanelBundle\Entity\MailWbList m');
		$entities = $this->getAclFilter()->apply($entities_raw, ['VIEW'], $user, 'm')->getResult();

        return $entities;
    }

}

