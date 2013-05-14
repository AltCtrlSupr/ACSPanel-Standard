<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class DnsDomainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: Do the addition of fields with suscriber
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        $security = $kernel->getContainer()->get('security.context');
        $user = $security->getToken()->getUser();
        $child_ids = $user->getIdChildIds();
        $superadmin = false;
        if($security->isGranted('ROLE_SUPER_ADMIN'))
            $superadmin = true;

        $builder
            ->add('domain','entity',array(
                'class' => 'ACS\ACSPanelBundle\Entity\Domain',
                'query_builder' => function(EntityRepository $er) use ($child_ids, $superadmin){
                    return $er->createQueryBuilder('d')
                        ->select('d')
                        ->where('d.is_httpd_alias != 1');
                        if(!$superadmin){
                            $er->andWhere('d.user IN (?1)')
                            ->setParameter('1', $child_ids);
                        }
                    }
                )
            )
            ->add('type', 'choice', array(
                'choices' => array(
                    'MASTER' => 'master',
                    'SLAVE' => 'slave'
                )
            ))
            ->add('master')
            ->add('service')
        ;

        if($security->isGranted('ROLE_ADMIN'))
            $builder->add('user');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\DnsDomain'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_pdnsdomaintype';
    }
}
