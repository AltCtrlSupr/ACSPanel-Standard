<?php

namespace ACS\ACSPanelWordpressBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class WPDomainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('domain', null, array('label' => 'domain.form.domain'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\Domain',
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_domaintype';
    }
}
