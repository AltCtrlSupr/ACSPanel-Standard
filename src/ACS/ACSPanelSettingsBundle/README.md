PanelSettingsBundle
===================

This bundles enables the ACSPanel settings.

The file to map the settings needed for the panel is app/main/config/panel_settings.yml. This file defines what are the system settings only editable by the superadmin, the user settings and the internal settings.
When you change this file to add new fields the next time one user goes to settings screen the new settings will be generated.

Install the bundle
------------------

Using composer:

    composer require acs/acspanel-settings

Add the next line to AppKernel:

    new ACS\ACSPanelSettingsBundle\ACSACSPanelSettingsBundle(),


Setting up
----------

Add the following config lines to configure your app setting entity:

    acsacs_panel_settings:
        setting_class:        ACS\ACSPanelBundle\Entity\PanelSetting # Required
        settings_class:       ACS\ACSPanelSettingsBundle\Doctrine\SettingManager # Required
        user_fields:
            setting_key:          ~
            label:                ~
            field_type:           ~
            default_value:        ~
            context:              ~
            choices:

                # Prototype
                name:                 []
            focus:                user_setting
        system_fields:
            setting_key:          ~
            label:                ~
            field_type:           ~
            choices:

                # Prototype
                name:                 []
            default_value:        ~
            context:              ~
            focus:                system_setting

Extends your entity from the Bundle entity (Remember to not include id field in doctrine definition)

    use ACS\ACSPanelSettingsBundle\Entity\ConfigSetting;

    class UserSetting extends ConfigSetting
    {
        ...
    }

Add the routes to app/routing.yml

    acs_settings:
        resource: "@ACSACSPanelSettingsBundle/Resources/config/routing.yml"
        prefix:   /
