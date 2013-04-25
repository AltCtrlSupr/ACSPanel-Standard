<?php

namespace ACS\ACSPanelBundle\Modules;

use Symfony\Component\DependencyInjection\Container;

use ACS\ACSPanelSettingsBundle\Entity\ConfigSetting;
use ACS\ACSPanelBundle\Entity\PanelSetting;
use ACS\ACSPanelBundle\Entity\Server;

use Doctrine\ORM\EntityManager;

class FtpdUser
{
    private $initialized = false;
    private $em;
    private $container;

    public function __construct(EntityManager $entityManager,Container $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

    /**
     * Returns true if uid exists in ftpduser table
     * @return boolean
     */
    public function getAvailableUid()
    {
        $em = $this->em;
        $rep = $em->getRepository('ACSACSPanelBundle:FtpdUser');

        $query = $rep->createQueryBuilder('fu')
            ->where("fu.id = (SELECT futemp.id FROM ACS\ACSPanelBundle\Entity\FtpdUser futemp WHERE futemp.uid = (SELECT MAX(futempx.uid) FROM ACS\ACSPanelBundle\Entity\FtpdUser futempx))")
            ->getQuery();

        $uid = $query->getArrayResult();
        if($uid)
            return $uid[0]['uid'] + 1;
        else
            return 1+2000;
    }
}
