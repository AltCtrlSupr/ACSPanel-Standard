<?php
/**
 * HttpdAliasRepository
 *
 * @author genar
 */
namespace ACS\ACSPanelBundle\Model;

use Doctrine\ORM\EntityRepository;
use ACS\ACSPanelBundle\Entity\FosUser;

class MailLogrcvdRepository extends EntityRepository
{
    public function findByUsers(Array $user)
    {
        $query = $this->_em->createQuery('SELECT ml FROM ACS\ACSPanelBundle\Entity\MailLogrcvd ml INNER JOIN ml.mail_domain md WHERE md.user IN (?1) ORDER BY ml.created_at DESC')->setParameter(1, $user);
        return $query->getResult();
    }

}

?>
