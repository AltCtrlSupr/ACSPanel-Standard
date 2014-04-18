<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Gedmo\Loggable\Entity\Repository\LogEntryRepository as EntityRepository;

class LogItemVersionsType extends AbstractType
{
    protected $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $this->entity;
        $builder
            ->add('version', 'entity', array(
                'mapped' => false,
                'class' => 'Gedmo\Loggable\Entity\LogEntry',
                'property' => 'version',
                'query_builder' => function(EntityRepository $er) use ($entity) {
                    return $er->createQueryBuilder('le')->where('le.objectId = ?1 AND le.objectClass = ?2')->setParameter('1', $entity->getId())->setParameter('2', get_class($entity));
                },
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gedmo\Loggable\Entity\LogEntry'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_logitemversionstype';
    }
}
