<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DnsDomainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $security = $options['token_storage'];
        $user = $security->getToken()->getUser();
        $user_domains = $options['domain_repository']->getUserViewable($user);
        $user_services = $options['service_repository']->getDNSServices($user);

        $builder
            ->add('domain', EntityType::class, array(
                    'class' => 'ACS\ACSPanelBundle\Entity\Domain',
                    'choices' => $user_domains,
                )
            )
            ->add('type', ChoiceType::class, array(
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

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\DnsDomain',
            'token_storage' => null,
            'domain_repository' => null,
            'service_repository' => null
        ));
    }

    public function getName()
    {
        return 'dnsdomaintype';
    }
}
