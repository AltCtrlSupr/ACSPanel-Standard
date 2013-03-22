<?php

namespace ACS\ACSPanelSettingsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelSettingsBundle\Entity\ConfigSetting;
use ACS\ACSPanelBundle\Entity\PanelSetting;
// TODO: Get this from config.yml
use ACS\ACSPanelBundle\Entity\FosUser;

use ACS\ACSPanelSettingsBundle\Form\ConfigSettingCollectionType;
use ACS\ACSPanelSettingsBundle\Form\ConfigSettingType;

/**
 * ConfigSetting controller.
 *
 */
class ConfigSettingController extends Controller
{

    /**
     * Displays a form to create a new ConfigSetting entity. Based on object settings template
     *
     */
    public function objectSettingsAction()
    {
        $class_name = $this->container->getParameter('acs_settings.setting_class');
        $user_fields = $this->container->getParameter("acs_settings.user_fields");

        // TODO: Check in this point if user has rights to access to that service settings
        if (true === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // $system_fields = $this->container->getParameter("acs_settings.system_fields");
            //$user_fields = array_merge($user_fields, $system_fields);
        }

        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.context')->getToken()->getUser();

        // $form_collection = new ConfigSettingCollectionType($user_fields);
        // Adding one form for each setting field
        foreach($user_fields as $id => $field_config){
            // TODO: To get from config.yml
            $setting = $em->getRepository('ACSACSPanelBundle:PanelSetting')->findOneBy(
                array(
                    'user' => $user->getId(),
                    'setting_key' => $field_config['setting_key'],
                    'focus' => $field_config['focus'],
                ));
            if(!count($setting)){
                $setting = new $class_name;
                $setting->setSettingKey($field_config['setting_key']);
                $setting->setValue($field_config['default_value']);
                $setting->setContext($field_config['context']);
                $setting->setLabel($field_config['label']);
                $setting->setType($field_config['field_type']);
                if(isset($field_config['choices']))
                    $setting->setChoices($field_config['choices']);
                $setting->setFocus($field_config['focus']);
                $user->addSetting($setting);
                $em->persist($user);
                $em->flush();
            }else{
                if(isset($field_config['choices']))
                    $setting->setChoices($field_config['choices']);
                $setting->setLabel($field_config['label']);
                $setting->setType($field_config['field_type']);
            }
        }

        $form   = $this->createForm(new ConfigSettingCollectionType($user_fields), $user);

        return $this->render('ACSACSPanelSettingsBundle:ConfigSetting:new.html.twig', array(
            'entity' => $user,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new ConfigSetting entity.
     *
     */
    public function userSettingsAction()
    {
        $class_name = $this->container->getParameter('acs_settings.setting_class');
        $user_fields = $this->container->getParameter("acs_settings.user_fields");
        if (true === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $system_fields = $this->container->getParameter("acs_settings.system_fields");
            $user_fields = array_merge($user_fields, $system_fields);
        }

        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.context')->getToken()->getUser();

        // $form_collection = new ConfigSettingCollectionType($user_fields);
        // Adding one form for each setting field
        foreach($user_fields as $id => $field_config){
            // TODO: To get from config.yml
            $setting = $em->getRepository('ACSACSPanelBundle:PanelSetting')->findOneBy(
                array(
                    'user' => $user->getId(),
                    'setting_key' => $field_config['setting_key'],
                    'focus' => $field_config['focus'],
                ));
            if(!count($setting)){
                $setting = new $class_name;
                $setting->setSettingKey($field_config['setting_key']);
                $setting->setValue($field_config['default_value']);
                $setting->setContext($field_config['context']);
                $setting->setLabel($field_config['label']);
                $setting->setType($field_config['field_type']);
                if(isset($field_config['choices']))
                    $setting->setChoices($field_config['choices']);
                $setting->setFocus($field_config['focus']);
                $user->addSetting($setting);
                $em->persist($user);
                $em->flush();
            }else{
                if(isset($field_config['choices']))
                    $setting->setChoices($field_config['choices']);
                $setting->setLabel($field_config['label']);
                $setting->setType($field_config['field_type']);
            }
        }

        $form   = $this->createForm(new ConfigSettingCollectionType($user_fields), $user);

        return $this->render('ACSACSPanelSettingsBundle:ConfigSetting:new.html.twig', array(
            'entity' => $user,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Edits an existing ConfigSetting entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $class_name = $this->container->getParameter('acs_settings.setting_class');
        $user_fields = $this->container->getParameter("acs_settings.user_fields");
        $em = $this->getDoctrine()->getManager();

        // TODO: Get from config.yml
        //$entity = $em->getRepository('ACSACSPanelBundle:FosUser')->find($id);
        $entity = $this->get('security.context')->getToken()->getUser();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ConfigSetting entity.');
        }

        $editForm = $this->createForm(new ConfigSettingCollectionType($user_fields), $entity);

        $editForm->bind($request);


        $postData = $request->request->get('acs_settings_usersettings');
        //print_r($postData);

        //if ($editForm->isValid()) {
            if(isset($postData['settings'])){
                $settings = $postData['settings'];

                foreach ($settings as $setting) {

                    // TODO: To get from config.yml
                    $panelsetting = $em->getRepository('ACSACSPanelBundle:PanelSetting')->findOneBy(
                        array(
                            'user' => $entity->getId(),
                            'setting_key' => $setting['setting_key'],
                            'focus' => 'user_setting',
                        ));
                    if($panelsetting){
                        $panelsetting->setValue($setting['value']);
                        $em->persist($panelsetting);
                    }else{
                        //$new_setting = new $class_name;
                        $new_setting = new PanelSetting();
                        $new_setting->setSettingKey($setting['setting_key']);
                        $new_setting->setValue($setting['value']);
                        $new_setting->setContext($setting['context']);
                        $new_setting->setFocus('user_setting');
                        $new_setting->setUser($entity);
                    }
                }
                $em->flush();
            //}

            return $this->redirect($this->generateUrl('settings'));
        }

        return $this->render('ACSACSPanelSettingsBundle:ConfigSetting:new.html.twig', array(
            'entity' => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
     * Deletes a ConfigSetting entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $this->get('acs.setting_manager')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ConfigSetting entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('settings'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
