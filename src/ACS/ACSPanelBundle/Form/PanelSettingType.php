<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PanelSettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('setting_key')
            ->add('value')
            ->add('context')
            ->add('focus')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('user')
            ->add('server')
            ->add('service')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\PanelSetting'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_panelsettingtype';
    }
}
