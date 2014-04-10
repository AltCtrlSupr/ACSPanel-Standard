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
    public function findByUsers(Array $user)
    {
        $query = $this->_em->createQuery('SELECT d FROM ACS\ACSPanelBundle\Entity\DB d WHERE d.user IN (?1)')->setParameter(1, $user);
        return $query->getResult();
    }
}

?>
