<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DnsRecordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $security   = $options['authorization_checker'];
        $user       = $options['token_storage']->getToken()->getUser();
        $child_ids  = $user->getIdChildIds();
        $superadmin = false;

        if($security->isGranted('ROLE_SUPER_ADMIN')) {
            $superadmin = true;
        }

        $builder
            ->add('dns_domain', EntityType::class, array(
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
            ->add('type', ChoiceType::class, array(
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\DnsRecord',
            'authorization_checker' => null,
            'token_storage' => null,
        ));
    }

    public function getName()
    {
        return 'dnsrecordtype';
    }
}
