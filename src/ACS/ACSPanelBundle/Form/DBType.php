<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DBType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: Do the addition of fields with suscriber
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        $service = $kernel->getContainer()->get('security.context');


        $builder
            ->add('name')
            ->add('service')
            ->add('database_users', 'collection', array(
                'type' => new DatabaseUserType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ))
        ;

        if($service->isGranted('ROLE_SUPER_ADMIN'))
            $builder->add('user');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\DB'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_dbtype';
    }
}
