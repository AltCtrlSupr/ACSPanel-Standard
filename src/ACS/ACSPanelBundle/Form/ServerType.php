<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ServerType extends AbstractType
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hostname')
            ->add('ip', new IpAddressType($this->container), array('label' => false))
            ->add('description')
        ;

        $security = $this->container->get('security.authorization_checker');
        if ($security->isGranted('ROLE_ADMIN')) {
            $builder->add('user');
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\Server'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_servertype';
    }
}
