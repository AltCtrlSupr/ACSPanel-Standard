<?php

namespace ACS\ACSPanelBackupBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ACSPanelBackupBundle:Default:index.html.twig', array('name' => $name));
    }
}
