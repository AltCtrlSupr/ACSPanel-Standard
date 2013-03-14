<?php

namespace ACS\ACSPanelSettingsBundle\Form;

// @todo: check to load the correct entity
//use ACS\ACSPanelSettingsBundle\Entity\ConfigSetting as ConfigSetting;
use ACS\ACSPanelBundle\Entity\PanelSetting as ConfigSetting;

use ACS\ACSPanelSettingsBundle\Form\ConfigSettingType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use ACS\ACSPanelSettingsBundle\Form\EventListener\AdaptFormSubscriber;

class ConfigSettingCollectionType extends AbstractType
{
    public $user_fields;

    public function __construct($user_fields)
    {
        $this->user_fields = $user_fields;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        //$subscriber = new AdaptFormSubscriber($builder->getFormFactory());
        //$builder->addEventSubscriber($subscriber);

        $builder->add('settings', 'collection', array(
            'type' => new ConfigSettingType(),
            'allow_add' => true,
            'prototype' => false,
        ));

        /*foreach($this->user_fields as $id => $field_config){

            $setting = new ConfigSetting();
            $setting->setSettingKey($field_config['setting_key']);
            $builder->add($id, new ConfigSettingType($setting), array('mapped' => false));
        }*/


    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // TODO: Get value from config.yml
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\FosUser',
            //'csrf_protection' => false,
            //'cascade_validation' => true,
            //'allow_extra_fields' => true,
        ));

    }


    public function getName()
    {
        return 'acs_settings_usersettings';
    }
}
