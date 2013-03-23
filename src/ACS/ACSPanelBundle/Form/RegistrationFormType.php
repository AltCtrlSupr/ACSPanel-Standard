<?php
namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder->add('first_name');
        $builder->add('last_name');
    }

    public function getName()
    {
        return 'acs_user_registration';
    }
}
