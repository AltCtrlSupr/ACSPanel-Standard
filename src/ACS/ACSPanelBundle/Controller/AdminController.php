<?php

namespace ACS\ACSPanelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('ACSACSPanelBundle:Default:superadminindex.html.twig', array('name' => 'Admin!'));
    }
}

