<?php
namespace ACS\ACSPanelBundle\Event\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ACS\ACSPanelBundle\Event\FilterMenuEvent;

class MenuSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'menu.admin.after.items'     => array(
                array('homeItems', 9000),
                //array('quickactionsItems', 90),
                array('adminItems', 80),
                array('domainItems', 70),
                array('httpdItems', 60),
                array('dnsItems', 50),
                array('mailItems', 40),
                array('databaseItems', 30),
                array('ftpItems', 20),
                array('logoutItems', -9000),
            ),
            'menu.reseller.after.items'     => array(
                array('homeItems', 9000),
                //array('quickactionsItems', 90),
                array('resellerItems', 80),
                array('domainItems', 70),
                array('httpdItems', 60),
                array('dnsItems', 50),
                array('mailItems', 40),
                array('databaseItems', 30),
                array('ftpItems', 20),
                array('logoutItems', -9000),
            ),
            'menu.user.after.items'     => array(
                array('homeItems', 9000),
                //array('quickactionsItems', 80),
                array('domainItems', 70),
                array('httpdItems', 60),
                array('dnsItems', 50),
                array('mailItems', 40),
                array('databaseItems', 30),
                array('ftpItems', 20),
                array('logoutItems', -9000),
            )

        );
    }

    public function homeItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        //$menu->addChild('Home', array('route' => 'acs_acspanel_homepage'));
    }

    public function quickactionsItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.quickactions.main', array( 'route' => null));
        $menu['menu.quickactions.main']->addChild('menu.quickactions.register_host', array( 'route' => 'acs_acspanel_register_host'));
    }

    public function resellerItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.reseller.main', array('route' => null));
        $menu['menu.reseller.main']->addChild('manu.reseller.users', array( 'route' => 'users'));
        $menu['menu.reseller.main']->addChild('menu.reseller.logs', array( 'route' => 'logs'));
    }

    public function adminItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.admin.main', array('route' => null));
        $menu['menu.admin.main']->addChild('menu.admin.users', array( 'route' => 'users'));
        $menu['menu.admin.main']->addChild('menu.admin.groups', array( 'route' => 'groups'));
        $menu['menu.admin.main']->addChild('menu.admin.plans', array( 'route' => 'plans'));
        $menu['menu.admin.main']->addChild('menu.admin.servers.main', array( 'route' => null));
        $menu['menu.admin.main']['menu.admin.servers.main']->addChild('menu.admin.servers.servers', array( 'route' => 'server'));
        $menu['menu.admin.main']['menu.admin.servers.main']->addChild('menu.admin.servers.services', array( 'route' => 'service'));
        $menu['menu.admin.main']['menu.admin.servers.main']->addChild('menu.admin.servers.service_types', array( 'route' => 'servicetype'));
        $menu['menu.admin.main']['menu.admin.servers.main']->addChild('menu.admin.servers.ip', array( 'route' => 'ipaddress'));
        $menu['menu.admin.main']->addChild('menu.admin.logs', array( 'route' => 'logs'));
    }

    public function domainItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.domain.main', array( 'route' => 'domain'));
    }

    public function httpdItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.httpd.main', array( 'route' => null));
        $menu['menu.httpd.main']->addChild('menu.httpd.hosts', array( 'route' => 'httpdhost'));
#        $menu['HTTPD']->addChild('Alias', array( 'route' => 'httpdalias'));
        $menu['menu.httpd.main']->addChild('menu.httpd.users', array( 'route' => 'httpduser'));
    }

    public function dnsItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.dns.main', array( 'route' => null));
        $menu['menu.dns.main']->addChild('menu.dns.domains', array( 'route' => 'dnsdomain'));
    }

    public function mailItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.mail.main', array( 'route' => null));
        $menu['menu.mail.main']->addChild('menu.mail.domain', array( 'route' => 'maildomain'));
        $menu['menu.mail.main']->addChild('menu.mail.mailbox', array( 'route' => 'mailmailbox'));
        $menu['menu.mail.main']->addChild('menu.mail.alias', array( 'route' => 'mailalias'));
        $menu['menu.mail.main']->addChild('menu.mail.wb_list', array ( 'route' => 'mailwblist' ));
        $menu['menu.mail.main']->addChild('menu.mail.received_msg_log', array ( 'route' => 'maillogrcvd' ));
    }

    public function databaseItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.database.main', array( 'route' => 'db'));
    }

    public function ftpItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.ftp.main', array( 'route' => 'ftpduser'));
    }



    public function logoutItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.logout.main', array( 'route' => 'fos_user_security_logout'));
    }
}
