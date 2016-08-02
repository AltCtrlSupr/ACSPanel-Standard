<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use ACS\ACSPanelBundle\Entity\HttpdHost;
use ACS\ACSPanelBundle\Entity\MailDomain;
use ACS\ACSPanelBundle\Entity\DB;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RegisterHostingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        switch ($options['flowStep']) {
        case 1:
            $builder->add('httpdhosts', CollectionType::class, array(
                'type' => new UserHttpdHostType(),
                'allow_add' => true,
                'data' => array(new HttpdHost()),
                'label' => null,
            ));
            break;
        case 2:
            $builder->add('maildomains', CollectionType::class, array(
                'type' => new MailDomainType(),
                'allow_add' => true,
                'data' => array(new MailDomain()),
                'label' => null,
            ));
            break;
        case 3:
            $builder->add('databases', CollectionType::class, array(
                'type' => new DBType(),
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
            'data_class' => 'ACS\ACSPanelUsersBundle\Entity\User', // should point to your user entity
        ));
    }

    public function getName() {
        return 'registerhosting';
    }

}
