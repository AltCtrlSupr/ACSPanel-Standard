<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UserHttpdHostType extends HttpdHostType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $user_domains = $container->get('domain_repository')->getNotHttpdAttachedUserViewable($user);
        $web_services = $container->get('service_repository')->getWebServices($user);
        $webproxy_services = $container->get('service_repository')->getWebproxyServices($user);

        $builder
            ->add('domain', EntityType::class, array(
                'class' => 'ACS\ACSPanelBundle\Entity\Domain',
                'label' => 'httpdhost.form.domain',
                'choices' => $user_domains
            ))
            ->add('configuration', null, array('label' => 'httpdhost.form.configuration'))
            ->add('cgi', null, array('label' => 'httpdhost.form.cgi'))
            ->add('ssi', null, array('label' => 'httpdhost.form.ssi'))
            ->add('php', null, array('label' => 'httpdhost.form.php'))
            ->add('ssl', null, array('label' => 'httpdhost.form.ssl'))
            ->add('service', null, array(
                'label' => 'httpdhost.form.service',
                'choices' => $web_services
            ))
            ->add('proxy_service', null, array(
                'label' => 'httpdhost.form.proxy_service',
                'choices' => $webproxy_services,
                'required' => false,
            ))
            ->add('add_www_alias', CheckboxType::class, array(
                'mapped' => false,
                'required' => false,
                'label' => 'httpdhost.form.addwwwalias'
            ))
            ->add('add_dns_record', CheckboxType::class,array(
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
