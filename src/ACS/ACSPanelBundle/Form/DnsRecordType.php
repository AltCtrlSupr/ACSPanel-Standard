<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DnsRecordType extends AbstractType
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
            ->add('dns_domain','entity',array(
                'label' => 'dnsdomain.form.dns_domain',
                'class' => 'ACS\ACSPanelBundle\Entity\DnsDomain',
                'required' => true,
                'query_builder' => function(EntityRepository $er) use ($child_ids, $superadmin){
                    $query = $er->createQueryBuilder('dd')
                        ->select('dd')
			->innerJoin('dd.domain','d');
                        if(!$superadmin){
                            $query->andWhere('d.user IN (?1)')
                            ->setParameter('1', $child_ids);
                        }
                        return $query;
                    }
                )
	    )
            ->add('name')
            ->add('type', 'choice', array(
                'choices' => array(
                    'NS' => 'NS',
                    'A' => 'A',
                    'AAAA' => 'AAAA',
                    'MX' => 'MX',
                    'CNAME' => 'CNAME',
                    'TXT' => 'TXT',
                    'SRV' => 'SRV',
                    'PTR' => 'PTR',
                    'LOC' => 'LOC',
                    'SSHFP' => 'SSHFP',
                    'SPF' => 'SPF',
                    'DKIM' => 'DKIM',
                    'SOA' => 'SOA',
                )
            ))
            ->add('content')
            ->add('ttl')
            ->add('prio')
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
