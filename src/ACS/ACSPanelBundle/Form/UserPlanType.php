<?php

namespace ACS\ACSPanelBundle\Form;

use ACS\ACSPanelBundle\Form\DataTransformer\UserToStringTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class UserPlanType extends AbstractType
{
    public $entity;
    public $em;

    public function __construct($entity, $em)
    {
        $this->entity = $entity;
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $this->entity;
        $id = $entity->getId();

        // this assumes that the entity manager was passed in as an option
        $entityManager = $this->em;
        $transformer = new UserToStringTransformer($entityManager);

        $builder
            ->add('uplans', null, array(
                'label' => 'Select a plan:',
            ));

        if($id){
            $builder
                ->add(
                    $builder->create('puser', HiddenType::class, array(
                        'data' => $entity->getId(),
                    ))->addModelTransformer($transformer)
                );
        }

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\UserPlan'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_userplantype';
    }
}
