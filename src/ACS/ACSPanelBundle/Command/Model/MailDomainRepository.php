<?php
/**
 * HttpdAliasRepository
 *
 * @author genar
 */
namespace ACS\ACSPanelBundle\Model;

use Doctrine\ORM\EntityRepository;
use ACS\ACSPanelBundle\Entity\FosUser;

class MailDomainRepository extends EntityRepository
{
    public function findByUser(FosUser $user)
    {
        $query = $this->_em->createQuery('SELECT a FROM ACS\ACSPanelBundle\Entity\MailDomain a WHERE a.user = ?1')->setParameter(1, $user->getId());
        return $query->getResult();
    }

    public function findByUsers(Array $ids)
    {
        $query = $this->_em->createQuery('SELECT a FROM ACS\ACSPanelBundle\Entity\MailDomain a WHERE a.user IN (?1)')->setParameter(1, $ids);
        return $query->getResult();
    }

}

?>
