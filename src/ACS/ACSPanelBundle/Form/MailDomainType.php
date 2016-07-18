<?php

namespace ACS\ACSPanelBundle\Form;

use ACS\ACSPanelBundle\Form\Base\ContainerAwareType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class MailDomainType extends ContainerAwareType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $security = $this->container->get('security.context');
        $user = $security->getToken()->getUser();
        $child_ids = $user->getIdChildIds();
        $user_services = $this->container->get('service_repository')->getMailServices($user);

        $superadmin = false;
        if($security->isGranted('ROLE_SUPER_ADMIN')) {
            $superadmin = true;
        }


        $builder
            ->add('domain','entity',array(
                'class' => 'ACS\ACSPanelBundle\Entity\Domain',
                'label' => 'maildomain.form.domain',
                'query_builder' => function(EntityRepository $er) use ($child_ids, $superadmin){
                    $query = $er->createQueryBuilder('d')
                        ->select('d')
                        ->where('d.is_httpd_alias != 1 OR d.is_httpd_alias IS NULL');
                        if(!$superadmin){
                            $query->andWhere('d.user IN (?1)')
                            ->setParameter('1', $child_ids);
                        }
                        return $query;
                    }
                )
            )
            ->add('description', null, array('label' => 'maildomain.form.description'))
            ->add('maxAliases', null, array('label' => 'maildomain.form.max_aliases'))
            ->add('maxMailboxes', null, array('label' => 'maildomain.form.max_mailboxes'))
            ->add('maxQuota', null, array('label' => 'maildomain.form.max_quota'))
            ->add('backupmx', null, array('label' => 'maildomain.form.backupmx'))
            ->add('service', null, array(
                'label' => 'maildomain.form.service',
                'choices' => $user_services
            ))
            ->add('add_dns_record','checkbox',array(
                'mapped' => false,
                'required' => false,
                'label' => 'maildomain.form.adddnsrecord'
            ))
        ;

        if($security->isGranted('ROLE_ADMIN'))
            $builder->add('user', null, array('label' => 'maildomain.form.user'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\MailDomain'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_maildomaintype';
    }
}
