<?php
namespace ACS\ACSPanelBackupBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ACS\ACSPanelBundle\Event\FilterMenuEvent;

class MenuSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'menu.before.items'     => array('beforeItems', 10),
            'menu.after.items'     => array('afterItems', 55)
        );
    }

    public function beforeItems(FilterMenuEvent $menu_filter)
    {
    }

    public function afterItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('Backups', array( 'route' => null));
        $menu['Backups']->addChild('Create new backup', array( 'route' => null));
        return $menu;
    }
}
