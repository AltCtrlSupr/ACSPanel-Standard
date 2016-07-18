<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WidgetController extends Controller
{
    public function quotaListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $current_user = $this->get('security.context')->getToken()->getUser();

        $max_httpd_host = $current_user->getPlanMax('HttpdHost');
        $used_httpd_host = $current_user->getUsedResource('HttpdHost',$em);

        $max_httpd_alias = $current_user->getPlanMax('HttpdAlias');
        $used_httpd_alias = $current_user->getUsedResource('HttpdAlias',$em,'Domain',array('user' => $current_user,'is_httpd_alias' => true));

        $max_httpd_user = $current_user->getPlanMax('HttpdUser');
        $used_httpd_user = $current_user->getUsedResource('HttpdUser',$em);

        $max_dns_domain = $current_user->getPlanMax('DnsDomain');
        $used_dns_domain = $current_user->getUsedResource('DnsDomain',$em);

        $max_dns_domain = $current_user->getPlanMax('DnsDomain');
        $used_dns_domain = $current_user->getUsedResource('DnsDomain',$em);

        $max_mail_domain = $current_user->getPlanMax('MailDomain');
        $used_mail_domain = $current_user->getUsedResource('MailDomain',$em);

        $max_mail_mailbox = $current_user->getPlanMax('MailMailbox');
        $used_mail_mailbox = $current_user->getUsedResource('MailMailbox',$em);

        $max_mail_alias= $current_user->getPlanMax('MailAlias');
        $used_mail_alias = $current_user->getUsedResource('MailAlias',$em);

        $max_mail_alias_domain = $current_user->getPlanMax('MailAliasDomain');
        $used_mail_alias_domain = $current_user->getUsedResource('MailAliasDomain',$em,'Domain',array('user' => $current_user,'is_mail_alias' => true));

        $max_ftpd = $current_user->getPlanMax('FtpdUser');
        $used_ftpd = $current_user->getUsedResource('FtpdUser',$em);


        return $this->render('ACSACSPanelBundle:Widget:quotaList.html.twig', array(
            'max_httpd_host' => $max_httpd_host,
            'used_httpd_host' => $used_httpd_host,
            'max_httpd_alias' => $max_httpd_alias,
            'used_httpd_alias' => $used_httpd_alias,
            'max_httpd_user' => $max_httpd_user,
            'used_httpd_user' => $used_httpd_user,
            'max_dns_domain' => $max_dns_domain,
            'used_dns_domain' => $used_dns_domain,
            'max_mail_domain' => $max_mail_domain,
            'used_mail_domain' => $used_mail_domain,
            'max_mail_mailbox' => $max_mail_mailbox,
            'used_mail_mailbox' => $used_mail_mailbox,
            'max_mail_alias' => $max_mail_alias,
            'used_mail_alias' => $used_mail_alias,
            'max_mail_alias_domain' => $max_mail_alias_domain,
            'used_mail_alias_domain' => $used_mail_alias_domain,
            'max_ftpd' => $max_ftpd,
            'used_ftpd' => $used_ftpd,
        ));
    }

    public function planListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $current_user = $this->get('security.context')->getToken()->getUser();
        $plans = $current_user->getPlans();
        //if(!count($plans))
            //throw $this->createNotFoundException('No plans found.');

        return $this->render('ACSACSPanelBundle:Widget:planList.html.twig', array('plans' => $plans));
    }

    public function helpTipAction()
    {
        $em = $this->getDoctrine()->getManager();
        $current_user = $this->get('security.context')->getToken()->getUser();
        $plans = $current_user->getPlans();

        // TODO: Create system to admin the helptips of each section
        $help_tip = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc rhoncus magna nisl. Maecenas ultrices venenatis turpis, quis lacinia leo pretium vitae. Pellentesque quis dolor sem, ut blandit leo. Donec adipiscing viverra mollis. Sed ipsum augue, pharetra a facilisis eget, volutpat eu felis. Maecenas id sapien mi. Sed fringilla orci quis quam porttitor gravida. Nullam nunc sem, posuere ut convallis eget, ultricies ut arcu. Nulla vitae sem sit amet nibh varius vestibulum at quis purus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla facilisi.  ";

        return $this->render('ACSACSPanelBundle:Widget:helpTip.html.twig', array('help_tip' => $help_tip));
    }

    public function newsAction()
    {
        //$em = $this->getDoctrine()->getManager();
        //$current_user = $this->get('security.context')->getToken()->getUser();
        //$plans = $current_user->getPlans();

        // TODO: Create system to admin the helptips of each section
        //$help_tip = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc rhoncus magna nisl. Maecenas ultrices venenatis turpis, quis lacinia leo pretium vitae. Pellentesque quis dolor sem, ut blandit leo. Donec adipiscing viverra mollis. Sed ipsum augue, pharetra a facilisis eget, volutpat eu felis. Maecenas id sapien mi. Sed fringilla orci quis quam porttitor gravida. Nullam nunc sem, posuere ut convallis eget, ultricies ut arcu. Nulla vitae sem sit amet nibh varius vestibulum at quis purus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla facilisi.  ";

        return $this->render('ACSACSPanelBundle:Widget:news.html.twig', array());
    }

}
