<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use ACS\ACSPanelSettingsBundle\Form\EntitySettingType;
use ACS\ACSPanelSettingsBundle\Event\SettingsEvents;
use ACS\ACSPanelSettingsBundle\Event\FilterUserFieldsEvent;

class ServiceType extends AbstractType
{
    public $container;

    public function __construct($container){
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Common fields
        $builder
            ->add('name')
            ->add('ip')
            ->add('server', null, array('required' => true))
        ;

        $container = $this->container;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($container) {
            $service = $event->getData();
            $form = $event->getForm();

            // check if the Product object is "new"
            // If no data is passed to the form, the data is "null".
            // This should be considered a new "Product"
            if (!$service || null === $service->getId()) {
                $form->add('type');
            } else {
                $settingsmanager = $this->container->get('acs.setting_manager');
                $fields = $settingsmanager->getObjectSettingsPrototype($service->getId());

                $form->add('settings', 'collection', array(
                    'type' =>  new EntitySettingType($container, $fields)
                ));
            }
        });

        $service = $this->container->get('security.context');
        if ($service->isGranted('ROLE_ADMIN')) {
            $builder->add('user');
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\Service'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_servicetype';
    }
}
