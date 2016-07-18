<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class DomainType extends AbstractType
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
        if($security->isGranted('ROLE_SUPER_ADMIN')) {
            $superadmin = true;
        }

        $builder
            ->add('domain', null, array('label' => 'domain.form.domain'))
            ->add('parent_domain','entity',array(
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
            ->add('add_dns_domain','checkbox',array(
                'mapped' => false,
                'required' => false,
                'label' => 'domain.form.adddnsdomain'
            ))
        ;

        if ($security->isGranted('ROLE_ADMIN')) {
            $builder->add('user', null, array('label' => 'domain.form.user'));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\Domain'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_domaintype';
    }
}
