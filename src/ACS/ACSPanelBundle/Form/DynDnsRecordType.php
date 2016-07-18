<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class DynDnsRecordType extends AbstractType
{
    private $container;

    public function __construct($container)
    {
      $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $security = $this->container->get('security.context');
        $user = $security->getToken()->getUser();
        $child_ids = $user->getIdChildIds();
        $superadmin = false;
        if($security->isGranted('ROLE_SUPER_ADMIN'))
            $superadmin = true;

        $builder
            ->add('subdomain', 'text', array(
                'mapped' => false
            ))
            ->add('dns_domain','entity',array(
                'label' => 'dnsdomain.form.dns_domain',
                'class' => 'ACS\ACSPanelBundle\Entity\DnsDomain',
                'required' => true,
                'query_builder' => function(EntityRepository $er) use ($child_ids, $superadmin){
                    $query = $er->createQueryBuilder('dd')
                        ->select('dd')
                        ->innerJoin('dd.domain','d')
                        ->where('dd.public = true');
                        return $query;
                    }
                )
            )
            ->add('content')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\DnsRecord'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_dnsrecordtype';
    }
}
