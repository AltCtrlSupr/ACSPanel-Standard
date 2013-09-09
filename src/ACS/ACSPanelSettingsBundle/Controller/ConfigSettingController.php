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
     * It creates the object settings specified
     * // TODO: Move to SettingManager
     */
    public function createObjectSettingsAction($object_id)
    {
        $em = $this->getDoctrine()->getManager();
        $class_name = $this->container->getParameter('acs_settings.setting_class');

        // Get the object fields
        // TODO: Decouple this
        $object = $em->getRepository('ACSACSPanelBundle:Service')->find($object_id);
        $object_fields = $object->getType()->getFieldTypes();

        // TODO: Check in this point if user has rights to access to that service settings
        // if (true === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            // $system_fields = $this->container->getParameter("acs_settings.system_fields");
            // $user_fields = array_merge($user_fields, $system_fields);
        // }


        $user = $this->get('security.context')->getToken()->getUser();

        // $form_collection = new ConfigSettingCollectionType($user_fields);
        // Adding one form for each setting field
        foreach($object_fields as $id => $field_config){
            // TODO: To get from config.yml
            $setting = $em->getRepository('ACSACSPanelBundle:PanelSetting')->findOneBy(
                array(
                    'user' => $user->getId(),
                    'setting_key' => $field_config->getSettingKey(),
                    'focus' => 'object_setting',
                    // TODO uncouple this
                    'service' => $object,
                ));

            if(!count($setting)){
                $setting = new $class_name;
                $setting->setSettingKey($field_config->getSettingKey());
                $setting->setValue($field_config->getDefaultValue());
                $setting->setContext($field_config->getContext());
                $setting->setLabel($field_config->getLabel());
                $setting->setType($field_config->getType());
                // TODO: implement choices
                // if(isset($field_config['choices']))
                    // $setting->setChoices($field_config['choices']);
                $setting->setFocus('object_setting');
                // TODO: Uncouple this
                $setting->setService($object);
                $user->addSetting($setting);
                $em->persist($user);
                $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('settings'));

    }

    /**
     * Load the settings array to pass to form
     *
     */
    public function loadUserFields()
    {
        $user_fields = array();

        $this->container->get('event_dispatcher')->dispatch(SettingsEvents::BEFORE_LOAD_USERFIELDS, new FilterUserFieldsEvent($user_fields,$this->container));

        array_merge($user_fields, $user_fields = $this->container->getParameter("acs_settings.user_fields"));

        $user = $this->get('security.context')->getToken()->getUser();

        // If is admins we load the global system settings
        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $user_fields = array_merge($user_fields, $system_fields = $this->container->getParameter("acs_settings.system_fields"));
        }

        $object_settings = $this->get('acs.setting_manager')->getObjectSettingsPrototype($user);

        $user_fields = array_merge($user_fields, $object_settings);

        $this->container->get('event_dispatcher')->dispatch(SettingsEvents::AFTER_LOAD_USERFIELDS, new FilterUserFieldsEvent($user_fields,$this->container));

        return $user_fields;
    }

    /**
     * Displays a form with all the user settings
     *
     */
    public function userSettingsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user_fields = $this->loadUserFields();

        $user = $this->get('security.context')->getToken()->getUser();

        $form = $this->createForm(new ConfigSettingCollectionType($user_fields, $em), $user);

        $contexts = $this->getContexts($user);

        return $this->render('ACSACSPanelSettingsBundle:ConfigSetting:new.html.twig', array(
            'entity' => $user,
            'contexts' => $contexts,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Returns the context used to organize the settings view
     *
     */
    public function getContexts($user)
    {
        $em = $this->getDoctrine()->getManager();
        $contexts_rep = $em->getRepository('ACSACSPanelBundle:PanelSetting');
        $query = $contexts_rep->createQueryBuilder('ps')
            ->select('ps.context')
            ->where('ps.user = ?1')
            ->groupBy('ps.context')
            ->orderBy('ps.context')
            ->setParameter('1',$user)
            ->getQuery();
        $contexts = $query->execute();

        return $contexts;
    }


    /**
     * Edits an existing ConfigSetting entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $class_name = $this->container->getParameter('acs_settings.setting_class');
        $user_fields = $this->loadUserFields();
        $em = $this->getDoctrine()->getManager();

        // TODO: Get from config.yml
        //$entity = $em->getRepository('ACSACSPanelBundle:FosUser')->find($id);
        $entity = $this->get('security.context')->getToken()->getUser();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ConfigSetting entity.');
        }

        $editForm = $this->createForm(new ConfigSettingCollectionType($user_fields,$em), $entity);

        $editForm->bind($request);


        $postData = $request->request->get('acs_settings_usersettings');

        // TODO: Check security issues not to call this method
        if ($editForm->isValid()) {
            if(isset($postData['settings'])){
                    $settings = $postData['settings'];

                    foreach ($settings as $setting) {

                        // TODO: To get from config.yml
                        $args = array(
                            'user' => $entity->getId(),
                            'setting_key' => $setting['setting_key'],
                        );
                        if(isset($setting['service_id'])){
                            $service = $em->getRepository('ACSACSPanelBundle:Service')->find($setting['service_id']);
                            $args['service'] = $service;
                        }
                        $panelsetting = $em->getRepository('ACSACSPanelBundle:PanelSetting')->findOneBy($args);
                        if($panelsetting){
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
