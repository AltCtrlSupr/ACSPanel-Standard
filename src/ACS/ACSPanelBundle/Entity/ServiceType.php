<?php


namespace ACS\ACSPanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServiceType
 */
class ServiceType
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
     * @var \ACS\ACSPanelBundle\Entity\ServiceType
     */
    private $parent_type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $field_types;

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
     * @return ServiceType
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
     * Set parentType
     *
     * @param \ACS\ACSPanelBundle\Entity\ServiceType $parentType
     * @return ServiceType
     */
    public function setParentType(\ACS\ACSPanelBundle\Entity\ServiceType $parentType = null)
    {
        $this->parent_type = $parentType;

        return $this;
    }

    /**
     * Get parentType
     *
     * @return \ACS\ACSPanelBundle\Entity\ServiceType
     */
    public function getParentType()
    {
        return $this->parent_type;
    }

    /**
     * Return the readable name of service type
     */
	public function __toString(){
		return $this->getName();
	}

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->field_types = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add fieldTypes
     *
     * @param \ACS\ACSPanelBundle\Entity\FieldType $fieldTypes
     * @return ServiceType
     */
    public function addFieldType(\ACS\ACSPanelBundle\Entity\FieldType $fieldTypes)
    {
        $this->field_types[] = $fieldTypes;

        return $this;
    }

    /**
     * Remove fieldTypes
     *
     * @param \ACS\ACSPanelBundle\Entity\FieldType $fieldTypes
     */
    public function removeFieldType(\ACS\ACSPanelBundle\Entity\FieldType $fieldTypes)
    {
        $this->field_types->removeElement($fieldTypes);
    }

    /**
     * Get fieldTypes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFieldTypes()
    {
        return $this->field_types;
    }



}