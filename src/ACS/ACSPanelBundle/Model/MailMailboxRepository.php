<?php
/**
 * HttpdAliasRepository
 *
 * @author genar
 */
namespace ACS\ACSPanelBundle\Model;

use ACS\ACSPanelUsersBundle\Entity\User;

use ACS\ACSPanelUsersBundle\Doctrine\AclEntityRepository;

class MailMailboxRepository extends AclEntityRepository
{
    public function findByUser(User $user)
    {
        $query = $this->_em->createQuery('SELECT m FROM ACS\ACSPanelBundle\Entity\MailMailbox m INNER JOIN m.mail_domain md INNER JOIN md.domain d WHERE d.user = ?1')->setParameter(1, $user->getId());
        return $query->getResult();
    }


    public function findByUsers(Array $user)
    {
        $query = $this->_em->createQuery('SELECT m FROM ACS\ACSPanelBundle\Entity\MailMailbox m INNER JOIN m.mail_domain md INNER JOIN md.domain d WHERE d.user IN (?1)')->setParameter(1, $user);
        return $query->getResult();
    }

    public function getUserViewable($user)
    {
		$entities_raw = $this->_em->createQuery('SELECT mb FROM ACS\ACSPanelBundle\Entity\MailMailbox mb');
		$entities = $this->getAclFilter()->apply($entities_raw, ['VIEW'], $user, 'mb')->getResult();

        return $entities;
    }


}
