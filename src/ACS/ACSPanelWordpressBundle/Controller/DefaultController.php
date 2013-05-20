<?php

namespace ACS\ACSPanelWordpressBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ACSACSPanelWordpressBundle:Default:index.html.twig', array('name' => $name));
    }
}
