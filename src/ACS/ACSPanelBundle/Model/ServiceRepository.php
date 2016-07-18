<?php
/**
 * ServiceRepository
 *
 * @author Genar
 */
namespace ACS\ACSPanelBundle\Model;

use ACS\ACSPanelUsersBundle\Doctrine\AclEntityRepository;

class ServiceRepository extends AclEntityRepository
{
    public function getDbServices($user)
    {
        $query = $this->_em->createQuery('
            SELECT s,st
            FROM ACS\ACSPanelBundle\Entity\Service s
            INNER JOIN s.type st
            LEFT JOIN st.parent_type pst
            WHERE st.name = ?1 OR pst.name = ?1 OR pst.name = ?2')
            ->setParameter(1, 'DB')
            ->setParameter(2, 'Database')
        ;
        return $this->getAclFilter()->apply($query, ['VIEW'], $user, 's')->getResult();
    }

    public function getDNSServices($user)
    {
        $query = $this->_em->createQuery('SELECT s,st FROM ACS\ACSPanelBundle\Entity\Service s INNER JOIN s.type st LEFT JOIN st.parent_type pst WHERE st.name = ?1 OR pst.name = ?1 OR pst.name = ?2')->setParameter(1, 'DNS')->setParameter(2, 'DNS');

        return $this->getAclFilter()->apply($query, ['VIEW'], $user, 's')->getResult();
    }

    public function getFTPServices($user)
    {
        $query = $this->_em->createQuery('SELECT s,st FROM ACS\ACSPanelBundle\Entity\Service s INNER JOIN s.type st LEFT JOIN st.parent_type pst WHERE st.name = ?1 OR pst.name = ?1 OR pst.name = ?2')->setParameter(1, 'FTP')->setParameter(2, 'ftp');

        return $this->getAclFilter()->apply($query, ['VIEW'], $user, 's')->getResult();
    }

    public function getMailServices($user)
    {
        $query = $this->_em->createQuery('SELECT s,st FROM ACS\ACSPanelBundle\Entity\Service s INNER JOIN s.type st LEFT JOIN st.parent_type pst WHERE st.name = ?1 OR pst.name = ?1 OR pst.name = ?2')->setParameter(1, 'Mail')->setParameter(2, 'SMTP');

        return $this->getAclFilter()->apply($query, ['VIEW'], $user, 's')->getResult();
    }

    public function getWebServices($user)
    {
        $query = $this->_em->createQuery('SELECT s,st FROM ACS\ACSPanelBundle\Entity\Service s INNER JOIN s.type st LEFT JOIN st.parent_type pst WHERE st.name LIKE ?1 OR pst.name LIKE ?1 OR st.name LIKE ?2 OR pst.name LIKE ?2')->setParameter(1, '%Web%')->setParameter(2, '%HTTP%');

        return $this->getAclFilter()->apply($query, ['VIEW'], $user, 's')->getResult();
    }

    public function getWebproxyServices($user) {
        $query = $this->_em->createQuery('SELECT s,st FROM ACS\ACSPanelBundle\Entity\Service s INNER JOIN s.type st LEFT JOIN st.parent_type pst WHERE st.name LIKE ?1 OR pst.name LIKE ?1 OR st.name LIKE ?2 OR pst.name LIKE ?2')->setParameter(1, '%Webproxy%')->setParameter(2, '%HTTP proxy%');

        return $this->getAclFilter()->apply($query, ['VIEW'], $user, 's')->getResult();
    }

    public function getUserViewable($user)
    {
        $entities_raw = $this->_em->createQuery('SELECT s FROM ACS\ACSPanelBundle\Entity\Service s');
        $entities = $this->getAclFilter()->apply($entities_raw, ['VIEW'], $user, 's')->getResult();

        return $entities;
    }
}
