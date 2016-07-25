<?php

namespace ACS\ACSPanelSettingsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelSettingsBundle\Entity\ConfigSetting;
use ACS\ACSPanelSettingsBundle\Event\SettingsEvents;
use ACS\ACSPanelSettingsBundle\Event\FilterUserFieldsEvent;
// TODO: Get this from config.yml
use ACS\ACSPanelBundle\Entity\PanelSetting;
// TODO: Get this from config.yml
use ACS\ACSPanelBundle\Model\SettingManager;
use ACS\ACSPanelSettingsBundle\Form\ConfigSettingCollectionType;
use ACS\ACSPanelSettingsBundle\Form\ConfigSettingType;

/**
 * ConfigSetting controller.
 */
class ConfigSettingController extends Controller
{
    /**
     * It creates the object settings specified
     */
    public function createObjectSettingsAction($object_id)
    {
        $em = $this->getDoctrine()->getManager();
        $class_name = $this->container->getParameter('acs_settings.setting_class');

        // Get the object fields
        $object = $em->getRepository('ACSACSPanelBundle:Service')->find($object_id);
        $object_fields = $object->getType()->getFieldTypes();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        // Adding one form for each setting field
        foreach($object_fields as $id => $field_config){
            $setting = $em->getRepository('ACSACSPanelBundle:PanelSetting')->findOneBy(
                array(
                    'user' => $user->getId(),
                    'setting_key' => $field_config->getSettingKey(),
                    'focus' => 'object_setting',
                    'service' => $object,
                )
            );

            if (!count($setting)) {
                $setting = new $class_name;
                $setting->setSettingKey($field_config->getSettingKey());
                $setting->setValue($field_config->getDefaultValue());
                $setting->setContext($field_config->getContext());
                $setting->setLabel($field_config->getLabel());
                $setting->setType($field_config->getType());
                $setting->setFocus('object_setting');
                $setting->setService($object);
                $user->addSetting($setting);
                $em->persist($user);
                $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('settings'));
    }

    /**
     * Displays a form with all the user settings
     */
    public function panelSettingsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $settingmanager = $this->get('acs.setting_manager');

        $user_fields = $settingmanager->loadUserFields();

        $object_settings = $settingmanager->getObjectSettingsPrototype($user);

        array_merge($user_fields, $object_settings);

        $form = $this->createForm(new ConfigSettingCollectionType($user_fields, $em), $user);

        $contexts = $settingmanager->getContexts($user);

        return $this->render('ACSACSPanelSettingsBundle:ConfigSetting:edit.html.twig', array(
            'entity' => $user,
            'contexts' => $contexts,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Edits an existing ConfigSetting entity.
     */
    public function updateAction(Request $request, $id)
    {
        $class_name = $this->container->getParameter('acs_settings.setting_class');
        $settingmanager = $this->get('acs.setting_manager');
        $user_fields = $settingmanager->loadUserFields();
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.token_storage')->getToken()->getUser();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ConfigSetting entity.');
        }

        $editForm = $this->createForm(new ConfigSettingCollectionType($user_fields,$em), $entity);

        $editForm->bind($request);


        $postData = $request->request->get('acs_settings_usersettings');

        if ($editForm->isValid()) {
            if (isset($postData['settings'])) {
                    $settings = $postData['settings'];

                    foreach ($settings as $setting) {
                        $args = array(
                            'user' => $entity->getId(),
                            'setting_key' => $setting['setting_key'],
                        );

                        if(isset($setting['service_id'])){
                            $service = $em->getRepository('ACSACSPanelBundle:Service')->find($setting['service_id']);
                            $args['service'] = $service;
                        }

                        $panelsetting = $em->getRepository('ACSACSPanelBundle:PanelSetting')->findOneBy($args);

                        if ($panelsetting && isset($setting['value'])) {
                            $panelsetting->setValue($setting['value']);
                            $em->persist($panelsetting);
                            $em->flush();
                        }
                    }
                    $em->flush();
            }
            return $this->redirect($this->generateUrl('settings'));
        }

        return $this->render('ACSACSPanelSettingsBundle:ConfigSetting:new.html.twig', array(
            'entity' => $entity,
            'form'   => $editForm->createView(),
        ));
    }
}
