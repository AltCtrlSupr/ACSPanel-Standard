<?php
namespace ACS\ACSPanelSettingsBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\DataEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormEvents;


/**
 * AdaptFormSuscriber
 *
 * @author genar
 */

class AdaptFormSubscriber implements EventSubscriberInterface
{
    private $factory;

    public function __construct(FormFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(DataEvent $event)
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

        switch($data->getType()){
            case 'select':
                $choices = $data->getChoices();
                $form->add($this->factory->createNamed('value','choice',$data->getValue(), array(
                    'label' => $data->getLabel(),
                    'choices' => $data->getChoices(),
                )));
                break;
            case 'text':
                $form->add($this->factory->createNamed('value','text',$data->getValue(),array('label' => $data->getLabel())));

                break;

        }
        $form->add($this->factory->createNamed('type','hidden',$data->getType(),array()));
    }

}

?>
