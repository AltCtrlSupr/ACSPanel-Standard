<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class FtpdUserType extends AbstractType
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $service = $this->container->get('security.token_storage');
        $user = $service->getToken()->getUser();
        $user_services = $this->container->get('service_repository')->getFTPServices($user);

        $builder
            ->add('userName')
            ->add('plain_password', 'password', array(
                'required' => true,
                'mapped' => false,
                'label' => 'common.password'
            ))
            ->add('dir','text',array(
                'label' => 'Directory name (it will be under "' . $user->getHomedir() . '/")',
                'required' => false,
            ))
            ->add('enabled')
            ->add('quota')
            ->add('service', null, array(
                'choices' => $user_services
            ))
        ;

        $allowed_users = $user->getChildUsers();
        $allowed_users->add($user);

        if($service->isGranted('ROLE_ADMIN'))
            $builder->add('user', null, array(
                'choices' => $allowed_users
            )
        );

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            if ($event->getData()->getId()) {
                $event->getForm()->add('plain_password', 'password', array(
                    'required' => false,
                    'mapped' => false,
                    'label' => 'common.password'
                ));
            }
        });
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
