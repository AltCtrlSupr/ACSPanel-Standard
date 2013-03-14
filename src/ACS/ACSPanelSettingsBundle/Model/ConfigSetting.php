<?php
namespace ACS\ACSPanelSettingsBundle\Model;

/**
 * ACS\ACSPanelSettingsBundle\Entity\ConfigSetting
 */
abstract class ConfigSetting
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $value;

    protected $key;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return ConfigSetting
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set setting_key
     *
     * @param string $settingKey
     * @return ConfigSetting
     */
    public function setSettingKey($settingKey)
    {
        $this->setting_key = $settingKey;

        return $this;
    }

    /**
     * Get setting_key
     *
     * @return string
     */
    public function getSettingKey()
    {
        return $this->setting_key;
    }



}
