<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('planName')
            ->add('maxPanelReseller')
            ->add('maxPanelUser')
            ->add('maxHttpdHost')
            ->add('maxHttpdAlias')
            ->add('maxHttpdUser')
            ->add('maxDnsDomain')
            ->add('maxMailDomain')
            ->add('maxMailMailbox')
            ->add('maxMailAlias')
            ->add('maxMailAliasDomain')
            ->add('maxFtpdUser')
            //->add('createdAt')
            //->add('updatedAt')
            //->add('users')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\Plan'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_plantype';
    }
}
