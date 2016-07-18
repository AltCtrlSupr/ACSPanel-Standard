<?php
/**
 * HttpdAliasRepository
 *
 * @author genar
 */
namespace ACS\ACSPanelBundle\Model;

use Doctrine\ORM\EntityRepository;

use ACS\ACSPanelUsersBundle\Doctrine\AclEntityRepository;

class MailLogrcvdRepository extends AclEntityRepository
{
    public function findByUsers(Array $user)
    {
        $query = $this->_em->createQuery('SELECT ml FROM ACS\ACSPanelBundle\Entity\MailLogrcvd ml INNER JOIN ml.mail_domain md WHERE md.user IN (?1) ORDER BY ml.createdAt DESC')->setParameter(1, $user);
        return $query->getResult();
    }

    public function getUserViewable($user)
    {
		$entities_raw = $this->_em->createQuery('SELECT ml FROM ACS\ACSPanelBundle\Entity\MailLogrcvd ml');
		$entities = $this->getAclFilter()->apply($entities_raw, ['VIEW'], $user, 'ml')->getResult();

        return $entities;
    }


}
