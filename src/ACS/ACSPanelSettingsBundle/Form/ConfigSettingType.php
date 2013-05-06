<?php

namespace ACS\ACSPanelSettingsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use ACS\ACSPanelSettingsBundle\Form\EventListener\AdaptFormSubscriber;

class ConfigSettingType extends AbstractType
{

	public $user_fields;
	public $em;

    public function __construct($em, $user_fields)
    {
        $this->user_fields = $user_fields;
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$user_fields = $this->user_fields;

        $builder
            ->add('setting_key','hidden')
            ->add('context', 'hidden')
            ->add('focus', 'hidden')
            //->add('type', 'hidden',array('mapped' => false))
        ;

        $subscriber = new AdaptFormSubscriber($builder->getFormFactory(), $user_fields);
		$builder->addEventSubscriber($subscriber);


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // TODO: Get value from config.yml
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\PanelSetting',
            //'virtual' => true,
        ));

    }

    public function getName()
    {
        return 'acs_settingsbundle_configsettingtype';
    }
}
