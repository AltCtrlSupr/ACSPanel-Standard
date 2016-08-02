<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DBType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $tokenStorage->getToken()->getUser();
        $user_services = $this->container->get('service_repository')->getDBServices($user);

        $builder
            ->add('name')
            ->add('description')
            ->add('service', null, array(
                'choices' => $user_services
            ))
            ->add('database_users', 'bootstrap_collection', array(
                'type' => new DatabaseUserType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\DB'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'token_storage' => null,
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_dbtype';
    }
}
