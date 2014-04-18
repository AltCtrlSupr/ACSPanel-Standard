<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class MailAliasType extends AbstractType
{
    public $container;

    public function __construct($container){
        $this->container = $container;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $this->container;
        $security = $container->get('security.context');
        $user = $security->getToken()->getUser();
        $child_ids = $user->getIdChildIds();
        $superadmin = false;
        if($security->isGranted('ROLE_SUPER_ADMIN'))
            $superadmin = true;


        $builder
            ->add('address')
            ->add('goto')
            ->add('mail_domain','entity',array(
                'class' => 'ACS\ACSPanelBundle\Entity\MailDomain',
                'query_builder' => function(EntityRepository $er) use ($child_ids, $superadmin){
                    $query = $er->createQueryBuilder('md')
                        ->select('md');
                        if(!$superadmin){
                            $query->innerJoin('md.domain','d')
                            ->where('d.user IN (?1)')
                            ->setParameter('1', $child_ids);
                        }
                        return $query;
                    }
                )
            )

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\MailAlias'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_mailaliastype';
    }
}
