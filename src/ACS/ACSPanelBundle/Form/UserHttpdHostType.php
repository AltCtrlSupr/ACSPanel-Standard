<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class UserHttpdHostType extends HttpdHostType
{
    public $container;

    public function __construct($container, $em){
        $this->container = $container;
        $this->em = $em;
    }

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

        $web_sercices_ids = $this->em->getRepository('ACS\ACSPanelBundle\Entity\ServiceType')->getWebServiceTypes();

        $builder
            ->add('domain','entity',array( 'class' => 'ACS\ACSPanelBundle\Entity\Domain', 'label' => 'httpdhost.form.domain'))
            ->add('configuration', null, array('label' => 'httpdhost.form.configuration'))
            ->add('cgi', null, array('label' => 'httpdhost.form.cgi'))
            ->add('ssi', null, array('label' => 'httpdhost.form.ssi'))
            ->add('php', null, array('label' => 'httpdhost.form.php'))
            ->add('ssl', null, array('label' => 'httpdhost.form.ssl'))
            // TODO: Add only http services
            ->add('service', null, array(
                'label' => 'httpdhost.form.service',
                'query_builder' => function(EntityRepository $er) use ($child_ids, $superadmin, $web_sercices_ids){
                    $query = $er->createQueryBuilder('s')
                        ->select('s')
                        ->innerJoin('s.type','st','WITH','st.id IN (?1)')
                        ->setParameter('1', $web_sercices_ids);
                        if(!$superadmin){
                            $query->andWhere('s.user IN (?2)')
                            ->setParameter('2', $child_ids);
                        }
                        return $query;
                    }
                )
            )
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
