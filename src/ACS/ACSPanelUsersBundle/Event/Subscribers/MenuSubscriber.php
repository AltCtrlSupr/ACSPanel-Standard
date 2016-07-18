<?php
namespace ACS\ACSPanelUsersBundle\Event\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ACS\ACSPanelBundle\Event\FilterMenuEvent;

class MenuSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'menu.admin.after.items'     => array(
                array('adminItems', 80),
            ),
            'menu.reseller.after.items'     => array(
                array('resellerItems', 80),
            ),
        );
    }

    public function resellerItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu['menu.reseller.main']->addChild('menu.reseller.users', array( 'route' => 'users', 'extras' => array('icon' => 'fa-users')));
    }

    public function adminItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu['menu.admin.main']->addChild('menu.admin.users', array( 'route' => 'users', 'extras' => array('icon' => 'fa-users')));
    }

}
