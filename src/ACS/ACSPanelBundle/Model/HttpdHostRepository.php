<?php
/**
 * HttpdAliasRepository
 *
 * @author genar
 */
namespace ACS\ACSPanelBundle\Model;

use Doctrine\ORM\EntityRepository;
use ACS\ACSPanelBundle\Entity\FosUser;

class HttpdHostRepository extends EntityRepository
{
    public function findByUser(FosUser $user)
    {
        $query = $this->_em->createQuery('SELECT h FROM ACS\ACSPanelBundle\Entity\HttpdHost h INNER JOIN h.domain d WHERE d.user = ?1')->setParameter(1, $user->getId());
        return $query->getResult();
    }

    public function findByUsers(Array $user)
    {
        $query = $this->_em->createQuery('SELECT h FROM ACS\ACSPanelBundle\Entity\HttpdHost h INNER JOIN h.domain d WHERE d.user IN (?1)')->setParameter(1, $user);
        return $query->getResult();
    }
}

?>
