<?php
namespace ACS\ACSPanelWordpressBundle\Event\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ACS\ACSPanelBundle\Event\FilterMenuEvent;

class WordpressMenuSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'menu.admin.after.items'     => array(
                array('wordpressItems', 10),
            ),
            'menu.user.after.items'     => array(
                array('wordpressItems', 10),
            )

        );
    }

    public function wordpressItems(FilterMenuEvent $menu_filter)
    {
        $menu = $menu_filter->getMenu();
        $menu->addChild('menu.wordpress.main', array( 'route' => 'wpsetup', 'extras' => array('icon' => 'fa-wordpress')));
    }
}
