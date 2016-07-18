<?php

namespace ACS\ACSPanelBundle\Form;

use ACS\ACSPanelBundle\Form\Base\ContainerAwareType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;

class UserHttpdUserType extends ContainerAwareType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $this->container;

        $user_httpd_hosts = $this
            ->container
            ->get('httpdhost_repository')
            ->getUserViewable($this->container->get('security.context')->getToken()->getUser())
        ;

        $builder
            ->add('name')
            ->add('password','password')
            ->add('protected_dir',null, array('required' => false))
            ->add('httpd_host', 'entity', array(
                'class' => 'ACSACSPanelBundle:HttpdHost',
                'choices' => $user_httpd_hosts,
                // 'empty_value' => 'messages.select.httpdhost',
                'required' => true
            ))
        ;
    }

    public function getName()
    {
        return 'acs_acspanelbundle_httpdusertype';
    }
}
