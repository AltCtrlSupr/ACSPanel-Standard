<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DynDnsRecordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $tokenStorage = $options['token_storage'];
        $authorization = $options['authorization_checker'];
        $user = $tokenStorage->getToken()->getUser();
        $child_ids = $user->getIdChildIds();
        $superadmin = $authorization->isGranted('ROLE_SUPER_ADMIN');

        $builder
            ->add('subdomain', TextType::class, array(
                'mapped' => false
            ))
            ->add('dns_domain', EntityType::class, array(
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\DnsRecord',
            'token_storage' => null,
            'authorization_checker' => null,
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_dnsrecordtype';
    }
}
