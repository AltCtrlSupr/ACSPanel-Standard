<?php

namespace ACS\ACSPanelWordpressBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ACS\ACSPanelBundle\Form\DBType;

class WPSetupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $authorizationChecker = $options['authorization_checker'];
        $tokenStorage = $options['token_storage'];

        $builder
            ->add('domain', WPDomainType::class, array(
                'mapped' => false
            ))
            ->add('database', DBType::class, array(
                'mapped' => false,
                'token_storage' => $tokenStorage
            ))
        ;

        if ($authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $builder->add('user');
        }
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelWordpressBundle\Entity\WPSetup',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'authorization_checker' => null,
            'token_storage' => null,
        ));
    }

    public function getName()
    {
        return 'acs_acspanelwordpressbundle_wpsetuptype';
    }
}
