<?php
/**
 * HttpdAliasRepository
 *
 * @author genar
 */
namespace ACS\ACSPanelBundle\Model;

use ACS\ACSPanelUsersBundle\Entity\User;

use ACS\ACSPanelUsersBundle\Doctrine\AclEntityRepository;

class MailAliasRepository extends AclEntityRepository
{
    public function findByUser(User $user)
    {
        $query = $this->_em->createQuery('SELECT a FROM ACS\ACSPanelBundle\Entity\MailAlias a INNER JOIN a.mail_domain md INNER JOIN md.domain d WHERE d.user = ?1')->setParameter(1, $user->getId());
        return $query->getResult();
    }

    public function findByUsers(Array $ids)
    {
        $query = $this->_em->createQuery('SELECT a FROM ACS\ACSPanelBundle\Entity\MailAlias a INNER JOIN a.mail_domain md INNER JOIN md.domain d WHERE d.user IN (?1)')->setParameter(1, $ids);
        return $query->getResult();
    }

    public function getUserViewable($user)
    {
		$entities_raw = $this->_em->createQuery('SELECT m FROM ACS\ACSPanelBundle\Entity\MailAlias m');
		$entities = $this->getAclFilter()->apply($entities_raw, ['VIEW'], $user, 'm')->getResult();

        return $entities;
    }

}

