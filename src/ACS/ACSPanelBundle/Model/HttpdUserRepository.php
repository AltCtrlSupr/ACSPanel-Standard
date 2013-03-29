<?php
/**
 * HttpdAliasRepository
 *
 * @author genar
 */
namespace ACS\ACSPanelBundle\Model;

use Doctrine\ORM\EntityRepository;
use ACS\ACSPanelBundle\Entity\FosUser;

class HttpdUserRepository extends EntityRepository
{
    public function findByUser(FosUser $user)
    {
        $query = $this->_em->createQuery('SELECT u FROM ACS\ACSPanelBundle\Entity\HttpdUser u INNER JOIN u.httpd_host h WHERE h.user = ?1')->setParameter(1, $user->getId());
        return $query->getResult();
    }
    public function findByUsers(Array $user)
    {
        $query = $this->_em->createQuery('SELECT u FROM ACS\ACSPanelBundle\Entity\HttpdUser u INNER JOIN u.httpd_host h WHERE h.user IN (?1)')->setParameter(1, $user);
        return $query->getResult();
    }
}

?>
