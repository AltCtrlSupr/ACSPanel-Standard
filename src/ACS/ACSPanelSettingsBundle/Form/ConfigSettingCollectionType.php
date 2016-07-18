<?php

namespace ACS\ACSPanelSettingsBundle\Form;

use ACS\ACSPanelBundle\Entity\PanelSetting as ConfigSetting;

use ACS\ACSPanelSettingsBundle\Form\ConfigSettingType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use ACS\ACSPanelSettingsBundle\Form\EventListener\AdaptFormSubscriber;

class ConfigSettingCollectionType extends AbstractType
{
    public $user_fields;
    public $em;

    public function __construct($user_fields, $em)
    {
        $this->user_fields = $user_fields;
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user_fields = $this->user_fields;

        $builder->add('settings', 'collection', array(
            'type' => new ConfigSettingType($this->em, $user_fields),
            'allow_add' => true,
            'prototype' => false,
            'options' => array()
        ));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelUsersBundle\Entity\User',
        ));

    }

    public function getName()
    {
        return 'acs_settings_usersettings';
    }
}
