<?php
namespace ACS\ACSPanelSettingsBundle\Event;

final class SettingsEvents
{
    // Before settings array is loaded
    const BEFORE_LOAD_USERFIELDS = 'settings.before.loadUserfields';
    // After settings array is loaded
    const AFTER_LOAD_USERFIELDS = 'settings.after.loadUserfields';
}
