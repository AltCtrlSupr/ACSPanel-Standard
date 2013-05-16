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

        $plans = $current_user->getPlans();

        $max_hosts = $current_user->getPlanMax('HttpdHost');
        $used_hosts = $current_user->getUsedResource('HttpdHost',$em);

        $max_ftpd = $current_user->getPlanMax('FtpdUser');
        $used_ftpd = $current_user->getUsedResource('FtpdUser',$em);

        $dashboard_widgets = array(
            'ACSACSPanelBundle:Widget:planList.html.twig',
            'ACSACSPanelBundle:Widget:quotaList.html.twig',
        );

        return $this->render('ACSACSPanelBundle:Default:dashboard.html.twig', array(
            'plans' => $plans,
            'max_hosts' => $max_hosts,
            'used_hosts' => $used_hosts,
            'max_ftpd' => $max_ftpd,
            'used_ftpd' => $used_ftpd,
            'dashboard_widgets' => $dashboard_widgets,
        ));
    }

    /**
     * @todo: implement a blobal search for all items
     */
    public function searchAction()
    {
    }
}
