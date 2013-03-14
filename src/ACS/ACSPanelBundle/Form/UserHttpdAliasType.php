<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserHttpdAliasType extends HttpdAliasType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alias')
            ->add('enabled')
            //->add('createdAt')
            //->add('updatedAt')
            ->add('httpd_host')
            ->add('add_www_alias','checkbox',array('property_path' => false, 'required' => false))
        ;
    }

}
