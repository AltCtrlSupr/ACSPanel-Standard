<?php
namespace ACS\ACSPanelSettingsBundle\Event\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ACS\ACSPanelSettingsBundle\Event\FilterUserFieldsEvent;

/**
 * Setting controller actions subscriber
 */
class SettingsSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'settings.before.loadUserfields'     => array(
                array('updateUserSettings', 10),
            ),
            'settings.after.loadUserfields'     => array(
                array('updateUserSettings', 10),
            ),
        );
    }

    public function updateUserSettings(FilterUserFieldsEvent $action_filter)
    {
    }
}
