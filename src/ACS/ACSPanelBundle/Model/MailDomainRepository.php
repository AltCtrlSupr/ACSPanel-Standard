<?php
/**
 * HttpdAliasRepository
 *
 * @author genar
 */
namespace ACS\ACSPanelBundle\Model;

use ACS\ACSPanelUsersBundle\Entity\User;

use ACS\ACSPanelUsersBundle\Doctrine\AclEntityRepository;

class MailDomainRepository extends AclEntityRepository
{
    public function findByUser(User $user)
    {
        $query = $this->_em->createQuery('SELECT a FROM ACS\ACSPanelBundle\Entity\MailDomain a WHERE a.user = ?1')->setParameter(1, $user->getId());
        return $query->getResult();
    }

    public function findByUsers(Array $ids)
    {
        $query = $this->_em->createQuery('SELECT a FROM ACS\ACSPanelBundle\Entity\MailDomain a WHERE a.user IN (?1)')->setParameter(1, $ids);
        return $query->getResult();
    }

    public function getUserViewable($user)
    {
		$entities_raw = $this->_em->createQuery('SELECT md FROM ACS\ACSPanelBundle\Entity\MailDomain md');
		$entities = $this->getAclFilter()->apply($entities_raw, ['VIEW'], $user, 'md')->getResult();

        return $entities;
    }

}

