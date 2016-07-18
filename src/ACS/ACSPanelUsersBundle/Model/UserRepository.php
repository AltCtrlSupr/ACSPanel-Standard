<?php
/**
 * @author Genar
 */
namespace ACS\ACSPanelUsersBundle\Model;

use ACS\ACSPanelUsersBundle\Doctrine\AclEntityRepository;

class UserRepository extends AclEntityRepository
{
    public function qbSuperadminUsers()
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->from('ACS\ACSPanelUsersBundle\Entity\User','usr')
            ->leftJoin('u.groups','g')
            ->where('g.roles LIKE :roles OR u.roles LIKE :roles')
            ->setParameter('roles', '%ROLE_SUPER_ADMIN%')
        ;
    }

    public function qbAdminUsers()
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->from('ACS\ACSPanelUsersBundle\Entity\User','usr')
            ->leftJoin('u.groups','g')
            ->where('g.roles LIKE :roles OR u.roles LIKE :roles')
            ->setParameter('roles', '%ROLE_ADMIN%')
        ;
    }

    public function getSuperadminUsers()
    {
        return $this->qbSuperadminUsers()
            ->getQuery()
            ->getResult()
        ;
    }

    public function getAdminUsers()
    {
        return $this->qbAdminUsers()
            ->getQuery()
            ->getResult()
        ;
    }

    public function getUserViewable($user)
    {
        $entities_raw = $this->_em->createQuery('SELECT u FROM ACS\ACSPanelUsersBundle\Entity\User u');
        $entities = $this->getAclFilter()->apply($entities_raw, ['VIEW'], $user, 'u')->getResult();

        return $entities;
    }
}
