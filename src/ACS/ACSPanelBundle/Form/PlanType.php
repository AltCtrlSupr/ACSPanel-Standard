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
            ->add('planName',null,array(
                'attr' => array(
                    'placeholder' => 'Plan name'
                )
            ))
            ->add('maxPanelReseller',null,array(
                'attr' => array(
                    'placeholder' => 'Max panel resellers'
                )
            ))
            ->add('maxPanelUser',null,array(
                'attr' => array(
                    'placeholder' => 'Max panel users'
                )
            ))
            ->add('maxDomain',null,array(
                'attr' => array(
                    'placeholder' => 'Max domains'
                )
            ))
            ->add('maxHttpdHost',null,array(
                'attr' => array(
                    'placeholder' => 'Max HTTPD hosts'
                )
            ))
            ->add('maxHttpdAlias',null,array(
                'attr' => array(
                    'placeholder' => 'Max HTTPD alias'
                )
            ))
            ->add('maxHttpdUser',null,array(
                'attr' => array(
                    'placeholder' => 'Max HTTPD users'
                )
            ))
            ->add('maxDnsDomain',null,array(
                'attr' => array(
                    'placeholder' => 'Max DNS domains'
                )
            ))
            ->add('maxMailDomain',null,array(
                'attr' => array(
                    'placeholder' => 'Max mail domains'
                )
            ))
            ->add('maxMailMailbox',null,array(
                'attr' => array(
                    'placeholder' => 'Max mailboxes'
                )
            ))
            ->add('maxMailAlias',null,array(
                'attr' => array(
                    'placeholder' => 'Max mail aliases'
                )
            ))
            ->add('maxMailAliasDomain',null,array(
                'attr' => array(
                    'placeholder' => 'Max mail alias domains'
                )
            ))
            ->add('maxFtpdUser',null,array(
                'attr' => array(
                    'placeholder' => 'Max FTPD users'
                )
            ))
            ->add('maxDb',null,array(
                'attr' => array(
                    'placeholder' => 'Max databases'
                )
            ))
            ->add('maxDbUser',null,array(
                'attr' => array(
                    'placeholder' => 'Max database users'
                )
            ))
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
