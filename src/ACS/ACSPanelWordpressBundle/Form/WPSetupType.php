<?php

namespace ACS\ACSPanelWordpressBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class WPSetupType extends AbstractType
{
    public $container;

    public function __construct($container){
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $this->container;
        $security = $container->get('security.context');
        $user = $security->getToken()->getUser();
        $child_ids = $user->getIdChildIds();
        $superadmin = false;
        if($security->isGranted('ROLE_SUPER_ADMIN'))
            $superadmin = true;

        $builder
            // TODO: Change form type to something simpler
            ->add('domain', new WPDomainType($container), array(
                'mapped' => false
            ))
            ->add('database', new \ACS\ACSPanelBundle\Form\DBType($container), array(
                'mapped' => false
            ))
        ;

        if($superadmin){
            $builder->add('user');
        }
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
