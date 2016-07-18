<?php

namespace ACS\ACSPanelUsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use ACS\ACSPanelBundle\Form\UserPlanType;
use ACS\ACSPanelBundle\Form\EventListener\UserFormFieldSuscriber;

class UserType extends AbstractType
{
    private $security;

    public function __construct($security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $builder->getData();

        $builder
            ->add('username', null, array('label' => 'user.form.username'))
            ->add('email', null, array('label' => 'user.form.email'))
            ->add('enabled', null, array('label' => 'user.form.enabled'))
            ->add('first_name', null, array('label' => 'user.form.first_name'))
            ->add('last_name', null, array('label' => 'user.form.last_name'));

        if($this->security->isGranted('ROLE_SUPER_ADMIN')){
           $builder->add('parent_user', null, array('label' => 'user.form.parent_user'));
        }

        if($this->security->isGranted('ROLE_ADMIN')){
           $builder->add('uid', null, array('label' => 'user.form.uid'));
           $builder->add('gid', null, array('label' => 'user.form.gid'));
        }


        $subscriber = new UserFormFieldSuscriber($builder->getFormFactory());
        $builder->addEventSubscriber($subscriber);

        if($this->security->isGranted('ROLE_RESELLER')){
            $builder
                ->add('groups', null, array(
                    'label' => 'user.form.groups')
                )
                ->add('puser', 'bootstrap_collection', array(
                    'type' => new UserPlanType($user, $options['em']),
                    'mapped' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'prototype_name' => '__name__',
                    'by_reference' => false,
                ))
                ->add('allowed_services', 'bootstrap_collection', array(
                    'type' => new UserAllowedServiceType($user, $options['em']),
                    'mapped' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'prototype_name' => '__name__',
                    'by_reference' => false,
                ))
            ;
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelUsersBundle\Entity\User'
        ));

        $resolver->setRequired(array(
            'em',
        ));

        $resolver->setAllowedTypes(array(
            'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_fosusertype';
    }
}
