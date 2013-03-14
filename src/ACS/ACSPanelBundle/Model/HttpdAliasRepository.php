<?php
/**
 * HttpdAliasRepository
 *
 * @author genar
 */
namespace ACS\ACSPanelBundle\Model;

use Doctrine\ORM\EntityRepository;
use ACS\ACSPanelBundle\Entity\FosUser;

class HttpdAliasRepository extends EntityRepository
{
    public function findByUser(FosUser $user)
    {
        $query = $this->_em->createQuery('SELECT a FROM ACS\ACSPanelBundle\Entity\HttpdAlias a INNER JOIN a.domain d INNER JOIN d.httpd_host h WHERE h.user = ?1')->setParameter(1, $user->getId());
        return $query->getResult();
    }
}

?>
