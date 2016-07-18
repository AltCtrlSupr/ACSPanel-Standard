<?php

namespace ACS\ACSPanelSettingsBundle\Tests\Model;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;
use ACS\ACSPanelSettingsBundle\Doctrine\SettingManager;

class SettingsManagerTest extends CommonTestCase
{
    private $settingManager;

    public function setUp()
    {
        $this->settingManager = $this->getContainer()->get('acs.setting_manager');
    }

    public function testSetSetting()
    {
        $setting = $this->settingManager->setSetting('test_setting', 'interal_setting', 'testing', 'User Settings');
        $this->assertNotNull($setting);

        $setting = $this->settingManager->setInternalSetting('test_setting', 'value');
        $this->assertNotNull($setting);
    }

    public function testGetSetting()
    {
        $setting = $this->settingManager->getSetting('last_used_uid', null);

        $this->assertNotNull($setting);
    }
}
