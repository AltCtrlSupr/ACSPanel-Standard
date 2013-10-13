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
        // TODO: Do the addition of fields with suscriber
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        $service = $kernel->getContainer()->get('security.context');
        //$container = $this->container;
        $container = $kernel->getContainer();

        $security = $container->get('security.context');

        $user = $security->getToken()->getUser();
        $child_ids = $user->getIdChildIds();
        $superadmin = false;
        if($security->isGranted('ROLE_SUPER_ADMIN'))
            $superadmin = true;

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
