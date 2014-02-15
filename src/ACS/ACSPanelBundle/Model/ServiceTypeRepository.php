<?php
/**
 * ServiceTypeRepository
 *
 * @author genar
 */
namespace ACS\ACSPanelBundle\Model;

use Doctrine\ORM\EntityRepository;
use ACS\ACSPanelBundle\Entity\FosUser;

class ServiceTypeRepository extends EntityRepository
{
    public function getDbServiceTypes()
    {
        $query = $this->_em->createQuery('SELECT st FROM ACS\ACSPanelBundle\Entity\ServiceType st LEFT JOIN st.parent_type pst WHERE st.name = ?1 OR pst.name = ?1 OR pst.name = ?2')->setParameter(1, 'DB')->setParameter(2, 'Database');
        $result = $query->getResult();
        $ids = array();

        foreach ($result as $key => $st) {
            $ids[] = $st->getId();
        }
        return $ids;

    }

    public function getDNSServiceTypes()
    {
        $query = $this->_em->createQuery('SELECT st FROM ACS\ACSPanelBundle\Entity\ServiceType st LEFT JOIN st.parent_type pst WHERE st.name = ?1 OR pst.name = ?1 OR pst.name = ?2')->setParameter(1, 'DNS')->setParameter(2, 'DNS');
        $result = $query->getResult();
        $ids = array();

        foreach ($result as $key => $st) {
            $ids[] = $st->getId();
        }
        return $ids;

    }

    public function getWebServiceTypes()
    {
        $query = $this->_em->createQuery('SELECT st FROM ACS\ACSPanelBundle\Entity\ServiceType st LEFT JOIN st.parent_type pst WHERE st.name LIKE ?1 OR pst.name LIKE ?1 OR pst.name LIKE ?2 OR pst.name LIKE ?3')->setParameter(1, '%Web%')->setParameter(2, '%Web%')->setParameter(3, '%HTTP%');
        $result = $query->getResult();
        $ids = array();

        foreach ($result as $key => $st) {
            $ids[] = $st->getId();
        }
        return $ids;

    }


}

?>
