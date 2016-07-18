<?php
/**
 * LogItemRepository
 *
 * @author Genar
 */
namespace ACS\ACSPanelBundle\Model;

use ACS\ACSPanelUsersBundle\Doctrine\AclEntityRepository;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;

class LogItemRepository extends AclEntityRepository implements LogEntryRepository
{
    public function getUserViewable($user)
    {
        $entities_raw = $this->_em
            ->createQuery('SELECT log FROM Gedmo\Loggable\Entity\LogEntry l ORDER BY l.loggedAt DESC')
        ;
        $entities = $this->getAclFilter()->apply($entities_raw, ['VIEW'], $user, 'l')->getResult();

        return $entities;
    }
}

