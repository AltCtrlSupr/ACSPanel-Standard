<?php

namespace ACS\ACSPanelAnsibleBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

use ACS\ACSPanelAnsibleBundle\Model\Inventory;

class DefaultController extends FOSRestController
{
    /**
     * @Rest\View()
     */
    public function indexAction()
    {
        $inventory = new Inventory();

        $hosts = $this->retrieveHosts();
        $inventory->setHosts($hosts);

        return $inventory;
    }

    private function retrieveHosts()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $this
            ->get('server_repository')
            ->getUserViewable(
                $this
                ->get('security.context')
                ->getToken()
                ->getUser()
            )
        ;

        return $entities;
    }
}
