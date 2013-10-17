<?php

namespace ACS\ACSPanelBundle\Form\HostingFlow;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class HostingDnsType extends \ACS\ACSPanelBundle\Form\HttpdHostType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('domain',new HostingDomainType(),array(
                'label' => 'httpdhost.form.domain'
            ))
        ;
    }

}
