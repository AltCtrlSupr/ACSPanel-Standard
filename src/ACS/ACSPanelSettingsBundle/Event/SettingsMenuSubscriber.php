<?php
namespace ACS\ACSPanelSettingsBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ACS\ACSPanelBundle\Event\FilterMenuEvent;

class SettingsMenuSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'menu.admin.after.items'     => array(
                array('settingsItems', 10),
            ),
            'menu.user.after.items'     => array(
                array('settingsItems', 10),
            )

        );
    }

    public function settingsItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        // TODO: Change the name of settings action
        $menu->addChild('Settings', array( 'route' => 'settings_new'));
    }
}
