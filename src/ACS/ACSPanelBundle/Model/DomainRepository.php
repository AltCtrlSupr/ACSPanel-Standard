<?php
/**
 * HttpdAliasRepository
 *
 * @author genar
 */
namespace ACS\ACSPanelBundle\Model;

use Doctrine\ORM\EntityRepository;
use ACS\ACSPanelBundle\Entity\FosUser;

class DomainRepository extends EntityRepository
{
    public function findByUser(FosUser $user)
    {
        $query = $this->_em->createQuery('SELECT d FROM ACS\ACSPanelBundle\Entity\Domain d WHERE d.user = ?1')->setParameter(1, $user->getId());
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
        $query = $this->_em->createQuery('SELECT d FROM ACS\ACSPanelBundle\Entity\Domain d WHERE d.is_httpd_alias = true AND d.user_id = ?1 ')->setParameter(1, $user->getId());
        return $query->getResult();
    }

}

?>
