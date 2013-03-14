<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @todo: Add stats widgets
     * @todo: Set slots in view to place the widgets
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $current_user = $this->get('security.context')->getToken()->getUser();

        $max_hosts = $current_user->getPlanMax('HttpdHost');
        $used_hosts = $current_user->getUsedResource('HttpdHost',$em);

        $max_ftpd = $current_user->getPlanMax('FtpdUser');
        $used_ftpd = $current_user->getUsedResource('FtpdUser',$em);


        //if (true === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->render('ACSACSPanelBundle:Default:superadminindex.html.twig', array(
                'max_hosts' => $max_hosts,
                'used_hosts' => $used_hosts,
                'max_ftpd' => $max_ftpd,
                'used_ftpd' => $used_ftpd,
            ));
        //}elseif(true === $this->get('security.context')->isGranted('ROLE_USER')){
            //return $this->render('ACSACSPanelBundle:Default:index.html.twig',  array('max_hosts' => $max_hosts));
        //}
    }

    /**
     * @todo: implement a blobal search for all items
     */
    public function searchAction()
    {
    }
}
