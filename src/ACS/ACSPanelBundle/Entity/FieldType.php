<?php


namespace ACS\ACSPanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FieldType
 * @todo Move to settingsbundle
 */
class FieldType
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $internal_name;

    /**
     * @var string
     */
    private $field_type;

   /**
     * @var string
     */
    private $setting_key;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $context;

    /**
     * @var string
     */
    private $default_value;

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
     * Set name
     *
     * @param string $name
     * @return FieldType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set internal_name
     *
     * @param string $internalName
     * @return FieldType
     */
    public function setInternalName($internalName)
    {
        $this->internal_name = $internalName;

        return $this;
    }

    /**
     * Get internal_name
     *
     * @return string
     */
    public function getInternalName()
    {
        return $this->internal_name;
    }

    /**
     * Set field_type
     *
     * @param string $fieldType
     * @return FieldType
     */
    public function setFieldType($fieldType)
    {
        $this->field_type = $fieldType;

        return $this;
    }

    /**
     * Get field_type
     *
     * @return string
     */
    public function getFieldType()
    {
        return $this->field_type;
    }
    /**
     * Set setting_key
     *
     * @param string $settingKey
     * @return FieldType
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
     * Set label
     *
     * @param string $label
     * @return FieldType
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
     * @return FieldType
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
     * Set context
     *
     * @param string $context
     * @return FieldType
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
     * Set default_value
     *
     * @param string $defaultValue
     * @return FieldType
     */
    public function setDefaultValue($defaultValue)
    {
        $this->default_value = $defaultValue;

        return $this;
    }

    /**
     * Get default_value
     *
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->default_value;
    }
}
