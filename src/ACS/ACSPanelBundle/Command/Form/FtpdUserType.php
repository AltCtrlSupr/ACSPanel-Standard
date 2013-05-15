<?php

namespace ACS\ACSPanelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FtpdUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: Do the addition of fields with suscriber
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        $service = $kernel->getContainer()->get('security.context');


        $builder
            ->add('userName')
            ->add('password')
            ->add('dir','text',array(
                'label' => 'Directory name (it will be in "'.$service->getToken()->getUser()->getHomedir().'/")',
            ))
            ->add('enabled')
            ->add('quota')
            ->add('service')
        ;

        if($service->isGranted('ROLE_ADMIN'))
            $builder->add('user');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ACS\ACSPanelBundle\Entity\FtpdUser'
        ));
    }

    public function getName()
    {
        return 'acs_acspanelbundle_ftpdusertype';
    }
}
