<?php


namespace ACS\ACSPanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FieldType
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
}