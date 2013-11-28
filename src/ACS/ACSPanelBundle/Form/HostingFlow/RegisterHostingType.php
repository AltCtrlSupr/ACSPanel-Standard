<?php

namespace ACS\ACSPanelBundle\Form\HostingFlow;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use ACS\ACSPanelBundle\Entity\HttpdHost;
use ACS\ACSPanelBundle\Entity\MailDomain;
use ACS\ACSPanelBundle\Entity\DB;

class RegisterHostingType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        switch ($options['flow_step']) {
        case 1:
            //$web_services = $this->em->getRepository('ACS\ACSPanelBundle\Entity\ServiceType')->getWebServiceTypes();
            $builder->add('domains', new HostingDomainType(), array(
                        'label' => null,
                        'mapped' => false
                    ))
                    ->add('service', 'entity',array(
                        'class' => 'ACSACSPanelBundle:Service',
                        'mapped' => false,
                        'property' => 'name'
                    ))
                    ->add('php_hosting','checkbox',array(
                        'label' => 'hosting.form.php_hosting',
                        'required' => false,
                        'value' => true,
                        'attr'     => array('checked'   => 'checked'),
                        'mapped' => false
                    ));

            break;
        case 2:
            $builder->add('add_a_records', 'checkbox', array(
                'label' => 'hosting.form.add_a_records',
                'required' => false,
                'value' => true,
                'attr'     => array('checked'   => 'checked'),
                'mapped' => false
            ));
            $builder->add('add_ns1_records', 'checkbox', array(
                'label' => 'hosting.form.add_ns1_records',
                'required' => false,
                'value' => true,
                'attr'     => array('checked'   => 'checked'),
                'mapped' => false
            ));
            $builder->add('add_ns2_records', 'checkbox', array(
                'label' => 'hosting.form.add_ns2_records',
                'required' => false,
                'value' => true,
                'attr'     => array('checked'   => 'checked'),
                'mapped' => false
            ));
            $builder->add('add_mx_records', 'checkbox', array(
                'label' => 'hosting.form.add_mx_records',
                'required' => false,
                'value' => true,
                'attr'     => array('checked'   => 'checked'),
                'mapped' => false
            ));
            $builder->add('servie', 'entity',array(
                'class' => 'ACSACSPanelBundle:Service',
                'mapped' => false,
                'property' => 'name'
            ));
            break;
        case 3:
            $builder->add('databases', 'collection', array(
                'type' => new HostingDatabasesType($options['container'],$options['em']),
                'label' => null,
                'mapped' => false
            ));
            $builder->add('maildomains', 'collection', array(
                'type' => new HostingDomainType(),
                'label' => null,
                'mapped' => false
            ));
            break;
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'flow_step' => 1,
            'domain' => '',
            'default_webserver' => '127.0.0.1',
            'container' => null,
            'em' => null,
            'data_class' => 'ACS\ACSPanelBundle\Entity\FosUser', // should point to your user entity
        ));
    }

    public function getName() {
        return 'register_hosting';
    }

}
