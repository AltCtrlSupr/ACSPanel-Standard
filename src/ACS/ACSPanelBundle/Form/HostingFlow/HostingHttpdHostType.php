<?php

namespace ACS\ACSPanelBundle\Form\HostingFlow;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class HostingHttpdHostType extends \ACS\ACSPanelBundle\Form\HttpdHostType
{
    public $container;

    /*public function __construct($container){
        $this->container = $container;
    }*/

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('domain',new HostingDomainType($container),array(
                'label' => 'httpdhost.form.domain'
            ))
            //->add('cgi', null, array('label' => 'httpdhost.form.cgi'))
            //->add('ssi', null, array('label' => 'httpdhost.form.ssi'))
            //->add('php', null, array('label' => 'httpdhost.form.php'))
        ;
    }

}
