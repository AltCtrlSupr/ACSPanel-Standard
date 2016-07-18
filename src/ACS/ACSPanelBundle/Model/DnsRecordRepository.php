<?php
/**
 * HttpdAliasRepository
 *
 * @author genar
 */
namespace ACS\ACSPanelBundle\Model;

use ACS\ACSPanelUsersBundle\Entity\User;

use ACS\ACSPanelUsersBundle\Doctrine\AclEntityRepository;

class DnsRecordRepository extends AclEntityRepository
{
    public function findByUser(User $user)
    {
        $query = $this->_em->createQuery('SELECT rec FROM ACS\ACSPanelBundle\Entity\DnsRecord rec INNER JOIN rec.dns_domain d WHERE d.user = ?1')->setParameter(1, $user->getId());
        return $query->getResult();
    }

    public function findByUsers(Array $user)
    {
        $query = $this->_em->createQuery('SELECT rec FROM ACS\ACSPanelBundle\Entity\DnsRecord rec INNER JOIN rec.dns_domain dns INNER JOIN dns.domain d WHERE d.user IN (?1)')->setParameter(1, $user);
        return $query->getResult();
    }

    public function findOneDynamic($user, $hostname)
    {
        $query = $this->_em->createQueryBuilder('r')
            ->select('r')
            ->where('r.name = ?1')
            ->from('ACS\ACSPanelBundle\Entity\DnsRecord', 'r')
            ->setParameter(1, $hostname)
            ->getQuery();

        $entity = $this->getAclFilter()->apply($query, ['EDIT'], $user, 'r')->getResult();

        if (!count($entity)) {
            return null;
        }

        return $entity[0];
    }

}

