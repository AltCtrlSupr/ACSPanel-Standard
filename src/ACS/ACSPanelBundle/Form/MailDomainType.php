<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class MailDomainType extends AbstractType
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
                'query_builder' => function(EntityRepository $er) use ($child_ids, $superadmin){
                    $query = $er->createQueryBuilder('s')
                        ->select('s')
                        ->innerJoin('s.type','t')
                        ->where('t.name LIKE ?1')
                        ->OrWhere('t.name LIKE ?2')
                        ->setParameter('1','%smtp%')
                        ->setParameter('2','%SMTP%');
                        // TODO: Check te best way to do this
                        /*if(!$superadmin){
                            $query->andWhere('s.user IN (?3)')
                            ->setParameter('3', $child_ids);
                        }*/
                        return $query;
                    }
                )
             )
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
