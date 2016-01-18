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

        return $inventory;
    }
}
