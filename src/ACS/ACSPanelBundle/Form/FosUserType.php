<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use ACS\ACSPanelBundle\Form\EventListener\UserFormFieldSuscriber;

class FosUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        // TODO: Do the addition of fields with suscriber
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }

        $service = $kernel->getContainer()->get('security.context');

        $user = $builder->getData();

        $builder
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('first_name')
            ->add('last_name');

        if($service->isGranted('ROLE_ADMIN')){
           $builder->add('uid');
           $builder->add('gid');
        }


        $subscriber = new UserFormFieldSuscriber ($builder->getFormFactory());
        $builder->addEventSubscriber($subscriber);

        $builder
            ->add('groups')
            ->add('puser', 'collection', array(
                'type' => new UserPlanType($user,$options['em']),
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
            'data_class' => 'ACS\ACSPanelBundle\Entity\FosUser'
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
