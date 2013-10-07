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
            'settings.after.loadUserfields'     => array(
                array('updateUserSettings', 10),
            ),
        );
    }



    /**
     * Create new added fields to user or missing fields
     *
     */
    public function updateUserSettings(FilterUserFieldsEvent $userfields_filter)
    {
        $settings_manager = $userfields_filter->getContainer()->get('acs.setting_manager');

        $user = $userfields_filter->getContainer()->get('security.context')->getToken()->getUser();

        $fields_template = $userfields_filter->getUserFields();

        //if($settings_manager->isUpdateAvailable($user, 'user_schema_version', $fields_template['user_schema_version']['default_value'])){
            // We have an update in the user fields
            $settings_manager->loadSettingDefaults($fields_template, $user);
        //}
    }
}
