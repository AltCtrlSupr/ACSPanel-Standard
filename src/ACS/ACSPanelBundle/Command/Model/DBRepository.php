<?php
/**
 * DBRepository
 *
 * @author ZUNbado
 */
namespace ACS\ACSPanelBundle\Model;

use Doctrine\ORM\EntityRepository;

class DBRepository extends EntityRepository
{
#    public function findByUser(FosUser $user)
#    {
#        $query = $this->_em->createQuery('SELECT h FROM ACS\ACSPanelBundle\Entity\HttpdHost h INNER JOIN h.domain d WHERE d.user = ?1')->setParameter(1, $user->getId());
#        return $query->getResult();
#    }
#
    public function findByUsers(Array $user)
    {
        $query = $this->_em->createQuery('SELECT d FROM ACS\ACSPanelBundle\Entity\DB d WHERE d.user IN (?1)')->setParameter(1, $user);
        return $query->getResult();
    }
}

?>
