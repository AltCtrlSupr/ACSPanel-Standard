<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserHttpdUserType extends HttpdUserType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('password','password')
            ->add('protected_dir')
            //->add('createdAt')
            //->add('updatedAt')
            ->add('httpd_host')
        ;
    }
}
