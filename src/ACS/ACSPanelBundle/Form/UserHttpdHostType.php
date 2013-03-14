<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class UserHttpdHostType extends HttpdHostType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('domain', new JustDomainType())
            ->add('domain','entity',array(
					'class' => 'ACS\ACSPanelBundle\Entity\Domain',
					'query_builder' => function(EntityRepository $er){
							return $er->createQueryBuilder('d')
								->select('d')
								->where('d.is_httpd_alias != 1');
						}
					)
				)
            ->add('configuration')
            ->add('cgi')
            ->add('ssi')
            ->add('php')
            ->add('service')
            ->add('add_www_alias','checkbox',array(
                'property_path' => false,
                'required' => false,
            ))
        ;
    }

}
