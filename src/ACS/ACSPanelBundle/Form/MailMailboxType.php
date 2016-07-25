<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class MailMailboxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: Do the addition of fields with suscriber
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        $security = $kernel->getContainer()->get('security.authorization_checker');
        $user = $security->getToken()->getUser();
        $child_ids = $user->getIdChildIds();
        $superadmin = false;
        if($security->isGranted('ROLE_SUPER_ADMIN'))
            $superadmin = true;

        $builder
            ->add('mail_domain','entity',array(
                'label' => 'mailbox.form.mail_domain',
                'class' => 'ACS\ACSPanelBundle\Entity\MailDomain',
                'query_builder' => function(EntityRepository $er) use ($child_ids, $superadmin){
                    $query = $er->createQueryBuilder('md')
                        ->select('md');
                        //->where('d.is_httpd_alias != 1');
                        if(!$superadmin){
                            $query->innerJoin('md.domain','d');
                            $query->where('d.user IN (?1)')
                            ->setParameter('1', $child_ids);
                        }
                        return $query;
                    }
                )
            )

            ->add('name', null, array('label' => 'mailbox.form.name'))
            ->add('username', null, array('label' => 'mailbox.form.username'))
            ->add('password', 'password', array('label' => 'mailbox.form.password'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\MailMailbox'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_mailmailboxtype';
    }
}
