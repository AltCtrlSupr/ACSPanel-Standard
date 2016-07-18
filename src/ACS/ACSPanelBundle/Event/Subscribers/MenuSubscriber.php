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
                array('settingsItems', 10),
                array('documentationItems', 10),
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
                array('settingsItems', 10),
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
                array('settingsItems', 10),
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
        $menu->addChild('menu.quickactions.main', array( 'route' => null, 'childrenAttributes' => array('class' => 'treeview-menu')));
        //$menu['menu.quickactions.main']->addChild('menu.quickactions.register_host', array( 'route' => 'acs_acspanel_register_host'));
    }

    public function resellerItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.reseller.main', array('route' => null));
        // $menu['menu.reseller.main']->addChild('menu.reseller.logs', array( 'route' => 'logs', 'extras' => array('icon' => 'fa-archive')));
    }

    public function adminItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.admin.main', array('route' => null, 'childrenAttributes' => array('class' => 'treeview-menu'), 'extras' => array('icon' => 'fa-lock')));
        $menu['menu.admin.main']->addChild('menu.admin.groups', array( 'route' => 'groups', 'extras' => array('icon' => 'fa-group')));
        $menu['menu.admin.main']->addChild('menu.admin.plans', array( 'route' => 'plans'));
        $menu['menu.admin.main']->addChild('menu.admin.servers.main', array( 'route' => null, 'childrenAttributes' => array('class' => 'treeview-menu')));
        $menu['menu.admin.main']['menu.admin.servers.main']->addChild('menu.admin.servers.servers', array( 'route' => 'server'));
        $menu['menu.admin.main']['menu.admin.servers.main']->addChild('menu.admin.servers.services', array( 'route' => 'service'));
        $menu['menu.admin.main']['menu.admin.servers.main']->addChild('menu.admin.servers.service_types', array( 'route' => 'servicetype'));
        $menu['menu.admin.main']['menu.admin.servers.main']->addChild('menu.admin.servers.ip', array( 'route' => 'ipaddress'));
        // $menu['menu.admin.main']->addChild('menu.admin.logs', array( 'route' => 'logs'));
    }

    public function domainItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.domain.main', array( 'route' => 'domain',  'extras' => array('icon' => 'fa-bookmark')));
    }

    public function httpdItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        // $menu->addChild('menu.httpd.newhosting', array( 'route' => 'acs_acspanel_register_host'));
        $menu->addChild('menu.httpd.main', array( 'route' => null, 'childrenAttributes' => array('class' => 'treeview-menu'), 'extras' => array('icon' => 'fa-globe' )));
        $menu['menu.httpd.main']->addChild('menu.httpd.hosts', array( 'route' => 'httpdhost'));
        $menu['menu.httpd.main']->addChild('menu.httpd.users', array( 'route' => 'httpduser'));
    }

    public function dnsItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.dns.main', array( 'route' => null, 'childrenAttributes' => array('class' => 'treeview-menu'), 'extras' => array('icon' => 'fa-search')));
        $menu['menu.dns.main']->addChild('menu.dns.domains', array( 'route' => 'dnsdomain'));
    }

    public function mailItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.mail.main', array( 'route' => null, 'childrenAttributes' => array('class' => 'treeview-menu'), 'extras' => array('icon' => 'fa-inbox') ));
        $menu['menu.mail.main']->addChild('menu.mail.domain', array( 'route' => 'maildomain'));
        $menu['menu.mail.main']->addChild('menu.mail.mailbox', array( 'route' => 'mailmailbox'));
        $menu['menu.mail.main']->addChild('menu.mail.alias', array( 'route' => 'mailalias'));
        $menu['menu.mail.main']->addChild('menu.mail.wb_list', array ( 'route' => 'mailwblist' ));
        $menu['menu.mail.main']->addChild('menu.mail.received_msg_log', array ( 'route' => 'maillogrcvd' ));
    }

    public function databaseItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.database.main', array( 'route' => 'db', 'extras' => array('icon' => 'fa-database') ));
    }

    public function ftpItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.ftp.main', array( 'route' => 'ftpduser', 'extras' => array('icon' => 'fa-cloud-upload')));
    }

    public function logoutItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.logout.main', array( 'route' => 'fos_user_security_logout', 'extras' => array('icon' => 'fa-sign-out' )));
    }

    public function settingsItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.settings.main', array( 'route' => 'settings', 'extras' => array('icon' => 'fa-gear')));
    }

    public function documentationItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.apidocumentation.main', array( 'route' => 'nelmio_api_doc_index', 'extras' => array('icon' => 'fa-key')));
    }

}
