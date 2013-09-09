<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class DBType extends AbstractType
{
    public $container;
    public $em;

    public function __construct($container, $em){
        $this->container = $container;
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $this->container;
        $service = $container->get('security.context');

        $db_services = $this->em->getRepository('ACS\ACSPanelBundle\Entity\ServiceType')->getDbServiceTypes();

        $builder
            ->add('name')
            ->add('description')
            ->add('service', 'entity', array(
                'class' => 'ACS\ACSPanelBundle\Entity\Service',
                'query_builder' => function(EntityRepository $er) use ($db_services){
                    $query = $er->createQueryBuilder('s')
                        ->select('s')
                        ->innerJoin('s.type','st','WITH','st.id IN (?1)')
                        ->setParameter('1', $db_services);
                        return $query;
                    }
                )
            )
            ->add('database_users', 'collection', array(
                'type' => new DatabaseUserType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ))
        ;

        if($service->isGranted('ROLE_SUPER_ADMIN'))
            $builder->add('user');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\DB'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_dbtype';
    }
}
