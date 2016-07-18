<?php

namespace ACS\ACSPanelSettingsBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\DataEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * AdaptFormSuscriber
 *
 * @author Genar
 */
class AdaptFormSubscriber implements EventSubscriberInterface
{
    private $factory;
    public $user_fields;

    public function __construct(FormFactoryInterface $factory, $user_fields = array())
    {
        $this->factory = $factory;
        $this->user_fields = $user_fields;
    }

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. You're only concerned with when
        // setData is called with an actual Entity object in it (whether new
        // or fetched with Doctrine). This if statement lets you skip right
        // over the null condition.
        if (null === $data) {
            return;
        }

        // Get the settings for that specific field
        $field_config = array();
        $fields = $this->user_fields;

        foreach ($fields as $field) {
            if ($data->getSettingKey() === $field['setting_key']) {
                $field_config = $field;
            }
        }

        // Service hidden input for id
        if ($data->getService()) {
            $form->add(
                $this->factory->createNamed(
                    'service_id',
                    'hidden',
                    $data->getService()->getId(),
                    array(
                        'mapped' => false,
                        'auto_initialize' => false
                    )
                )
            );
        }

        // Adding field according to field configuration
        if (!empty($field_config)) {
            switch ($field_config['field_type']) {
                case 'select':
                    $choices = $field_config['choices'];
                    $form->add($this->factory->createNamed('value','choice',$data->getValue(), array(
                        'label' => $field_config['label'],
                        'choices' => $choices,
                        'auto_initialize' => false,
                    )));
                    break;
                case 'hidden':
                    $form->add($this->factory->createNamed('value','hidden',$data->getValue(),array(
                        'label' => $field_config['label'],
                        'auto_initialize' => false,
                        'required' => false
                    )));
                    break;
                case 'text':
                    $form->add($this->factory->createNamed('value','text',$data->getValue(),array(
                        'label' => $field_config['label'],
                        'auto_initialize' => false,
                        'required' => false
                    )));
                    break;
                case 'password':
                    $form->add($this->factory->createNamed('value','password',$data->getValue(),array(
                        'label' => $field_config['label'],
                        'auto_initialize' => false,
                        'required' => false
                    )));
                    break;
            }
        }
    }
}
