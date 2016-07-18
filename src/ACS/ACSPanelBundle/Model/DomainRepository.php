<?php
/**
 * DomainRepository
 *
 * @author Genar Trias Ortiz <genar@acs.li>
 */
namespace ACS\ACSPanelBundle\Model;

use ACS\ACSPanelUsersBundle\Entity\User;
use ACS\ACSPanelUsersBundle\Doctrine\AclEntityRepository;

class DomainRepository extends AclEntityRepository
{
    public function getUserViewable($user)
    {
        $entities_raw = $this->_em->createQuery('SELECT d FROM ACS\ACSPanelBundle\Entity\Domain d');
        $entities = $this->getAclFilter()->apply($entities_raw, ['VIEW'], $user, 'd')->getResult();

        return $entities;
    }

    /**
     * Get all the domains without any httpdhost attached
     */
    public function getNotHttpdAttachedUserViewable($user)
    {
        $entities_raw = $this->_em->createQuery('SELECT d FROM ACS\ACSPanelBundle\Entity\Domain d LEFT JOIN d.httpd_host h WHERE h.id IS NULL');

        $entities = $this->getAclFilter()->apply($entities_raw, ['VIEW'], $user, 'd')->getResult();

        return $entities;
    }

    /**
     * @deprecated
     */
    public function findByUser(User $user)
    {
        $query = $this->_em->createQuery('SELECT d FROM ACS\ACSPanelBundle\Entity\Domain d WHERE d.user = ?1')->setParameter(1, $user->getId());
        return $query->getResult();
    }

    /**
     * @deprecated
     */
    public function findByUsers(Array $ids)
    {
        $query = $this->_em->createQuery('SELECT d FROM ACS\ACSPanelBundle\Entity\Domain d WHERE d.user IN (?1)')->setParameter(1, $ids);
        return $query->getResult();
    }

    /**
     * Return the domains that are aliases
     *
     * @return Collection
     */
    public function findAliases()
    {
        $query = $this->_em->createQuery('SELECT d FROM ACS\ACSPanelBundle\Entity\Domain d WHERE d.is_httpd_alias = true');
        return $query->getResult();
    }

    /**
     * Return the domains that are aliases for a specific user
     *
     */
    public function findAliasesByUser($user)
    {
        $query = $this->_em->createQuery('
            SELECT d
            FROM ACS\ACSPanelBundle\Entity\Domain d
            WHERE d.is_httpd_alias = true
            AND d.user_id = ?1 '
        )
        ->setParameter(1, $user->getId());
        return $query->getResult();
    }
}
