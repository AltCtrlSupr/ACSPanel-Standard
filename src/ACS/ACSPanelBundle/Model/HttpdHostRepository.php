<?php
/**
 * HttpdAliasRepository
 *
 * @author genar
 */
namespace ACS\ACSPanelBundle\Model;

use ACS\ACSPanelUsersBundle\Entity\User;

use ACS\ACSPanelUsersBundle\Doctrine\AclEntityRepository;

class HttpdHostRepository extends AclEntityRepository
{
    public function findByUser(User $user)
    {
        $query = $this->_em->createQuery('SELECT h FROM ACS\ACSPanelBundle\Entity\HttpdHost h INNER JOIN h.domain d WHERE d.user = ?1')->setParameter(1, $user->getId());
        return $query->getResult();
    }

    public function findByUsers(Array $user)
    {
        $query = $this->_em->createQuery('SELECT h FROM ACS\ACSPanelBundle\Entity\HttpdHost h INNER JOIN h.domain d WHERE d.user IN (?1)')->setParameter(1, $user);
        return $query->getResult();
    }

    public function getUserViewable($user)
    {
        $entities_raw = $this->_em->createQuery('SELECT h,d,pd FROM ACS\ACSPanelBundle\Entity\HttpdHost h INNER JOIN h.domain d LEFT JOIN d.parent_domain pd');
        $entities = $this->getAclFilter()->apply($entities_raw, ['VIEW'], $user, 'h')->getResult();

        return $entities;
    }

	public function search($term, $user)
	{
        $query = $this->_em->createQueryBuilder('h')
            ->where('h.id = ?1')
            ->orWhere('h.name LIKE ?1')
            ->orWhere('h.configuration LIKE ?1')
            ->orWhere('h.domain LIKE ?1')
            ->setParameter('1',$term)
            ->getQuery();

        $entities = $this->getAclFilter()->apply($query, ['VIEW'], $user, 'h')->getResult();

        return $entities;
	}

}
