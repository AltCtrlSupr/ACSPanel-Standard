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
        $query = $this->_em->createQuery('SELECT st FROM ACS\ACSPanelBundle\Entity\ServiceType st LEFT JOIN st.parent_type pst WHERE st.name = ?1 OR pst.name = ?1 OR pst.name = ?2')->setParameter(1, 'DB')->setParameter(1, 'Database');
        $result = $query->getResult();
        $ids = array();

        foreach ($result as $key => $st) {
            $ids[] = $st->getId();
        }
        return $ids;

    }
}

?>
