<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class UserHttpdHostType extends HttpdHostType
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
            ->add('domain','entity',array(
                'class' => 'ACS\ACSPanelBundle\Entity\Domain',
                'label' => 'httpdhost.form.domain',
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
            ->add('configuration', null, array('label' => 'httpdhost.form.configuration'))
            ->add('cgi', null, array('label' => 'httpdhost.form.cgi'))
            ->add('ssi', null, array('label' => 'httpdhost.form.ssi'))
            ->add('php', null, array('label' => 'httpdhost.form.php'))
            ->add('ssl', null, array('label' => 'httpdhost.form.ssl'))
            ->add('service', null, array('label' => 'httpdhost.form.service'))
            ->add('proxy_service', null, array('label' => 'httpdhost.form.proxy_service'))
            ->add('add_www_alias','checkbox',array(
                'mapped' => false,
                'required' => false,
                'label' => 'httpdhost.form.addwwwalias'
            ))
            ->add('add_dns_record','checkbox',array(
                'mapped' => false,
                'required' => false,
                'label' => 'httpdhost.form.adddnsrecord'
            ))
            ->add('certificate', null, array('label' => 'httpdhost.form.certificate'))
            ->add('certificate_key', null, array('label' => 'httpdhost.form.certificate_key'))
            ->add('certificate_chain', null, array('label' => 'httpdhost.form.certificate_chain'))
            ->add('certificate_authority', null, array('label' => 'httpdhost.form.certificate_authority'))
        ;
    }

}
