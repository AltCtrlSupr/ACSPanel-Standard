<?php
/**
 * HttpdAliasRepository
 *
 * @author genar
 */
namespace ACS\ACSPanelBundle\Model;

use Doctrine\ORM\EntityRepository;
use ACS\ACSPanelBundle\Entity\FosUser;

class MailAliasRepository extends EntityRepository
{
    public function findByUser(FosUser $user)
    {
        $query = $this->_em->createQuery('SELECT a FROM ACS\ACSPanelBundle\Entity\MailAlias a INNER JOIN a.mail_domain md INNER JOIN md.domain d WHERE d.user = ?1')->setParameter(1, $user->getId());
        return $query->getResult();
    }

    public function findByUsers(Array $ids)
    {
        $query = $this->_em->createQuery('SELECT a FROM ACS\ACSPanelBundle\Entity\MailAlias a INNER JOIN a.mail_domain md INNER JOIN md.domain d WHERE d.user IN (?1)')->setParameter(1, $ids);
        return $query->getResult();
    }

}

?>
