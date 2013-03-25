<?php
namespace ACS\ACSPanelSettingsBundle\Entity;

use ACS\ACSPanelSettingsBundle\Model\ConfigSetting as AbstractConfigSetting;

/**
 * ACS\ACSPanelSettingsBundle\Entity\ConfigSetting
 */
abstract class ConfigSetting extends AbstractConfigSetting
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $setting_key;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    private $context;

    /**
     * @var string
     */
    private $focus;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $choices;

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
     * Set context
     *
     * @param string $context
     * @return ConfigSetting
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set focus
     *
     * @param string $focus
     * @return ConfigSetting
     */
    public function setFocus($focus)
    {
        $this->focus = $focus;

        return $this;
    }

    /**
     * Get focus
     *
     * @return string
     */
    public function getFocus()
    {
        return $this->focus;
    }
    /**
     * Set label
     *
     * @param string $label
     * @return ConfigSetting
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return ConfigSetting
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set choices
     *
     * @param array $choices
     * @return ConfigSetting
     */
    public function setChoices($choices)
    {
        $this->choices = $choices;

        return $this;
    }

    /**
     * Get choices
     *
     * @return array
     */
    public function getChoices()
    {
        return $this->choices;
    }


}