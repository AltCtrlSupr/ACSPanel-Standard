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
            $builder->add('domains', new HostingDomainType(), array(
                        'label' => null,
                    ))
                    ->add('php_hosting','checkbox',array(
                        'label' => 'httpdhost.form.php_hosting',
                        'required' => false,
                        'value' => true,
                        'mapped' => false
                    ));

            break;
        case 2:
            $builder->add('dns', 'collection', array(
                'type' => new HostingDnsType(),
                'allow_add' => true,
                'data' => array(new DB()),
                'label' => null,
            ));
            break;
        case 3:
            $builder->add('domains', new HostingDomainType(), array(
                'label' => null,
            ));
            break;
        }
    }

    public function getName() {
        return 'register_hosting';
    }

}
