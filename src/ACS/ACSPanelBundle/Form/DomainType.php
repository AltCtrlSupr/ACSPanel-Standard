<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DomainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $security = $options['token_storage'];
        $authorization = $options['authorization_checker'];
        $user = $security->getToken()->getUser();
        $child_ids = $user->getIdChildIds();

        $superadmin = $authorization->isGranted('ROLE_SUPER_ADMIN');

        $builder
            ->add('domain', null, array('label' => 'domain.form.domain'))
            ->add('parent_domain', EntityType::class, array(
                'label' => 'domain.form.parent_domain',
                'class' => 'ACS\ACSPanelBundle\Entity\Domain',
                'required' => false,
                'query_builder' => function(EntityRepository $er) use ($child_ids, $superadmin){
                    $query = $er->createQueryBuilder('d')
                        ->select('d')
                        ->where('d.is_httpd_alias != 1 OR d.is_httpd_alias IS NULL');
                        if (!$superadmin) {
                            $query->andWhere('d.user IN (?1)')
                            ->setParameter('1', $child_ids);
                        }
                        return $query;
                    }
                )
            )
            ->add('is_httpd_alias', null, array('label' => 'domain.form.is_httpd_alias'))
            ->add('is_dns_alias', null, array('label' => 'domain.form.is_dns_alias'))
            ->add('is_mail_alias', null, array('label' => 'domain.form.is_mail_alias'))
            ->add('add_dns_domain', CheckboxType::class, array(
                'mapped' => false,
                'required' => false,
                'label' => 'domain.form.adddnsdomain'
            ))
        ;

        if ($authorization->isGranted('ROLE_ADMIN')) {
            $builder->add('user', null, array('label' => 'domain.form.user'));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\Domain',
            'token_storage' => null,
            'authorization_checker' => null,
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_domaintype';
    }
}
