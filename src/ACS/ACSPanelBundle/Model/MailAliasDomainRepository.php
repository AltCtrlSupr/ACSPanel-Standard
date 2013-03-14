<?php
/**
 * HttpdAliasRepository
 *
 * @author genar
 */
namespace ACS\ACSPanelBundle\Model;

use Doctrine\ORM\EntityRepository;
use ACS\ACSPanelBundle\Entity\FosUser;

class MailAliasDomainRepository extends EntityRepository
{
    public function findByUser(FosUser $user)
    {
        $query = $this->_em->createQuery('SELECT a FROM ACS\ACSPanelBundle\Entity\MailAliasDomain a INNER JOIN a.alias_domain d WHERE d.user = ?1')->setParameter(1, $user->getId());
        return $query->getResult();
    }
}

?>
