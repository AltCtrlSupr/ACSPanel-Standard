<?php

namespace ACS\ACSPanelWordpressBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use ACS\ACSPanelBundle\Form\UserHttpdHostType;
use ACS\ACSPanelBundle\Form\DBType;

class WPSetupType extends AbstractType
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
            /*->add('httpd_host','entity',array(
                'class' => 'ACS\ACSPanelBundle\Entity\HttpdHost',
                'query_builder' => function(EntityRepository $er) use ($child_ids, $superadmin){
                    $query = $er->createQueryBuilder('h')
                        ->select('h');
                        if(!$superadmin){
                            $query->innerJoin('h.domain','d')
                            ->where('d.user IN (?1)')
                            ->setParameter('1', $child_ids);
                        }
                        return $query;
                    }
                )
                )*/

            // TODO: Change form type to something simpler
            ->add('httpd_host', new UserHttpdHostType())


            ->add('database_user','entity',array(
                'class' => 'ACS\ACSPanelBundle\Entity\DatabaseUser',
                'query_builder' => function(EntityRepository $er) use ($child_ids, $superadmin){
                    $query = $er->createQueryBuilder('dbu')
                        ->select('dbu');
                        if(!$superadmin){
                            $query->innerJoin('dbu.db','db')
                            ->where('db.user IN (?1)')
                            ->setParameter('1', $child_ids);
                        }
                        return $query;
                    }
                )
                );

            //->add('db', new DBType($container, $em));

            if($superadmin){
                $builder->add('user');
            }
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelWordpressBundle\Entity\WPSetup'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelwordpressbundle_wpsetuptype';
    }
}
