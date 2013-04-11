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
        $menu->addChild('Home', array('route' => 'acs_acspanel_homepage'));
    }

    public function quickactionsItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('Quick actions', array( 'route' => null));
        $menu['Quick actions']->addChild('Add hosting', array( 'route' => 'acs_acspanel_register_host'));
    }

    public function adminItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('Admin', array('route' => null));
        $menu['Admin']->addChild('Panel Users', array( 'route' => 'users'));
        $menu['Admin']->addChild('Panel Groups', array( 'route' => 'groups'));
        $menu['Admin']->addChild('Plans', array( 'route' => 'plans'));
        $menu['Admin']->addChild('Servers', array( 'route' => null));
        $menu['Admin']['Servers']->addChild('Servers', array( 'route' => 'server'));
        $menu['Admin']['Servers']->addChild('Services', array( 'route' => 'service'));
        $menu['Admin']['Servers']->addChild('Service Types', array( 'route' => 'servicetype'));
        $menu['Admin']['Servers']->addChild('IP Addresses', array( 'route' => 'ipaddress'));
        $menu['Admin']->addChild('Logs', array( 'route' => 'logs'));
    }

    public function domainItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('Domains', array( 'route' => 'domain'));
    }

    public function httpdItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('HTTPD', array( 'route' => null));
        $menu['HTTPD']->addChild('Hosts', array( 'route' => 'httpdhost'));
#        $menu['HTTPD']->addChild('Alias', array( 'route' => 'httpdalias'));
        $menu['HTTPD']->addChild('Users', array( 'route' => 'httpduser'));
    }

    public function dnsItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('DNS', array( 'route' => null));
        $menu['DNS']->addChild('Domains', array( 'route' => 'dnsdomain'));
    }

    public function mailItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('Mail', array( 'route' => null));
        $menu['Mail']->addChild('Domain', array( 'route' => 'maildomain'));
        $menu['Mail']->addChild('Mailbox', array( 'route' => 'mailmailbox'));
        $menu['Mail']->addChild('Alias', array( 'route' => 'mailalias'));
		  $menu['Mail']->addChild('W/B List', array ( 'route' => 'mailwblist' ));
		  $menu['Mail']->addChild('Received msg log', array ( 'route' => 'maillogrcvd' ));
    }

    public function databaseItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('Databases', array( 'route' => 'db'));
        //$menu['Databases']->addChild('Databases', array( 'route' => 'db'));
        //$menu['Databases']->addChild('Users', array( 'route' => 'databaseuser'));
    }

    public function ftpItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('FTP', array( 'route' => 'ftpduser'));
    }



    public function logoutItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('Logout', array( 'route' => 'fos_user_security_logout'));
    }
}
