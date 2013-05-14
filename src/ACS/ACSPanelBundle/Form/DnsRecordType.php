<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DnsRecordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // TODO: Show only user relevant dns_domains
            ->add('dns_domain')
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
