<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @todo: Delegate this to WidgetController
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $current_user = $this->get('security.context')->getToken()->getUser();

        return $this->render('ACSACSPanelBundle:Default:dashboard.html.twig');
    }

    /**
     * @todo: implement a blobal search for all items
     */
    public function searchAction()
    {
    }
}
