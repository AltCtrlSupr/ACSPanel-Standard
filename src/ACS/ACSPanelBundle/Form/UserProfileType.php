<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserProfileType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder,$options);
        $user = $builder->getData();
        $builder
            ->add('username', TextType::class, array(
                'read_only' => true
            ));

    }

    public function getName()
    {
        return 'acs_acspanelbundle_userprofiletype';
    }
}
