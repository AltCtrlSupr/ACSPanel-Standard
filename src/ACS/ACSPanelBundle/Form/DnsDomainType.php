<?php

namespace ACS\ACSPanelBundle\Form;

use ACS\ACSPanelBundle\Form\Base\ContainerAwareType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class DnsDomainType extends ContainerAwareType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $security = $this->container->get('security.context');
        $user = $security->getToken()->getUser();
        $user_domains = $this->container->get('domain_repository')->getUserViewable($user);
        $user_services = $this->container->get('service_repository')->getDNSServices($user);

        $builder
            ->add('domain','entity',array(
                    'class' => 'ACS\ACSPanelBundle\Entity\Domain',
                    'choices' => $user_domains,
                )
            )
            ->add('type', 'choice', array(
                'choices' => array(
                    'MASTER' => 'master',
                    'SLAVE' => 'slave'
                )
            ))
            ->add('master')
            ->add('service', null, array(
                'choices' => $user_services
            ))
            ->add('public')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\DnsDomain'
        ));
    }

    public function getName()
    {
        return 'dnsdomaintype';
    }
}
