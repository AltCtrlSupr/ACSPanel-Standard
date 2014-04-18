<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HttpdUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('host')
            ->add('name')
            ->add('password')
            ->add('protected_dir')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('httpd_host')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\HttpdUser'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_httpdusertype';
    }
}
