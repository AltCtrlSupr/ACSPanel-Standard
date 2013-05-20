<?php

namespace ACS\ACSPanelWordpressBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WPSetupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enabled')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('httpd_host')
            ->add('database_user')
            ->add('user')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelWordpressBundle\Entity\WPSetup'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelwordpressbundle_wpsetuptype';
    }
}
