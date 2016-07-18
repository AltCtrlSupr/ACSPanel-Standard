<?php

namespace ACS\ACSPanelUsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Form\Type\GroupFormType;

class FosGroupType extends GroupFormType
{

    /**
     * @todo: Add roles looking security.yml or config.yml
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array('label' => 'form.group_name', 'translation_domain' => 'FOSUserBundle'));
        $builder->add('roles', 'choice', array(
            'choices' => array(
                'ROLE_USER' => 'User',
                'ROLE_RESELLER' => 'Reseller',
                'ROLE_ADMIN' => 'Admin',
                'ROLE_SUPER_ADMIN' => 'Super Admin',
            ),
            'multiple' => true,
        ));
    }

    public function __construct(){
        parent::__construct('ACS\ACSPanelUsersBundle\Entity\FosGroup');
    }

}
