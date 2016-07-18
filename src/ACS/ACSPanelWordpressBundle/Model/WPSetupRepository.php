<?php
/**
 * WPSetupRepository
 *
 * @author Genar Trias Ortiz <genar@acs.li>
 */
namespace ACS\ACSPanelWordpressBundle\Model;

use Doctrine\ORM\EntityRepository;
use ACS\ACSPanelUsersBundle\Entity\User;

class WPSetupRepository extends EntityRepository
{
    public function findByUser(User $user)
    {
        $query = $this->_em->createQuery('SELECT w FROM ACS\ACSPanelWordpressBundle\Entity\WPSetup w  WHERE w.user = ?1')->setParameter(1, $user->getId());
        return $query->getResult();
    }

    public function findByUsers(Array $user)
    {
        $query = $this->_em->createQuery('SELECT w FROM ACS\ACSPanelWordpressBundle\Entity\WPSetup w WHERE w.user IN (?1)')->setParameter(1, $user);
        return $query->getResult();
    }
}

