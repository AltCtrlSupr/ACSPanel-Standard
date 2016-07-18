<?php

namespace ACS\ACSPanelSettingsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use ACS\ACSPanelSettingsBundle\Form\EventListener\AdaptFormSubscriber;

class EntitySettingType extends AbstractType
{
    public $fields;
    public $container;

    public function __construct($container, $fields)
    {
        $this->fields = $fields;
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $fields = $this->fields;

        $builder
            ->add('setting_key','hidden')
            ->add('context', 'hidden')
            ->add('focus', 'hidden')
        ;

        $subscriber = new AdaptFormSubscriber($builder->getFormFactory(), $fields);
        $builder->addEventSubscriber($subscriber);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\PanelSetting',
        ));
    }

    public function getName()
    {
        return 'acs_settingsbundle_configsettingtype';
    }
}
