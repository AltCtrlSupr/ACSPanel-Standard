<?php

namespace ACS\ACSPanelBundle\Form\HostingFlow;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class HostingDomainType extends \ACS\ACSPanelBundle\Form\DomainType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('domain', null, array('label' => 'domain.form.domain'))
	;
    }
}
