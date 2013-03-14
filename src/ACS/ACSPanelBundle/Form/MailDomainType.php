<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MailDomainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('domain')
            ->add('description')
            ->add('maxAliases')
            ->add('maxMailboxes')
            ->add('maxQuota')
            ->add('transport')
            ->add('backupmx')
            ->add('service')
        ;
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
