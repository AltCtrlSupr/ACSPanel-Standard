<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FtpdUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userName')
            ->add('password')
            ->add('uid')
            ->add('gid')
            ->add('dir')
            ->add('enabled')
            ->add('quota')
            ->add('service')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\FtpdUser'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_ftpdusertype';
    }
}
