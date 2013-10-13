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
            $builder->add('httpdhosts', 'collection', array(
                'type' => new HostingHttpdHostType(),
                'allow_add' => true,
                'data' => array(new HttpdHost()),
                'label' => null,
            ));
            break;
        case 2:
            $builder->add('databases', 'collection', array(
                'type' => new HostingDBType(),
                'allow_add' => true,
                'data' => array(new DB()),
                'label' => null,
            ));
            break;
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'flowStep' => 0,
            'data_class' => 'ACS\ACSPanelBundle\Entity\FosUser', // should point to your user entity
        ));
    }

    public function getName() {
        return 'registerhosting';
    }

}
