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
     * Lists all ConfigSetting entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $this->get('acs.setting_manager')->findAll();

        return $this->render('ACSACSPanelSettingsBundle:ConfigSetting:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a ConfigSetting entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('acs.setting_manager')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ConfigSetting entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelSettingsBundle:ConfigSetting:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new ConfigSetting entity.
     *
     */
    public function newAction()
    {
        $class_name = $this->container->getParameter('acs_settings.setting_class');
        $user_fields = $this->container->getParameter("acs_settings.user_fields");

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
                    'focus' => 'user_setting',
                ));
            if(!count($setting)){
                $setting = new $class_name;
                $setting->setSettingKey($field_config['setting_key']);
                $setting->setValue($field_config['default_value']);
                $setting->setContext($field_config['context']);
                $setting->setLabel($field_config['label']);
                $setting->setFocus('user_setting');
                //$setting->setUser($user);
                $user->addSetting($setting);
                $em->persist($user);
                $em->flush();
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

        print_r($_POST);

        // TODO: Get from config.yml
        //$entity = $em->getRepository('ACSACSPanelBundle:FosUser')->find($id);
        $entity = $this->get('security.context')->getToken()->getUser();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ConfigSetting entity.');
        }

        $editForm = $this->createForm(new ConfigSettingCollectionType($user_fields), $entity);

        $editForm->bind($request);


        $postData = $request->request->get('acs_settings_usersettings');

        if ($editForm->isValid()) {
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
                        //$new_setting->setLabel($setting['label']);
                        $new_setting->setFocus('user_setting');
                        $new_setting->setUser($entity);
                    }
                }
                $em->flush();
            }

            return $this->redirect($this->generateUrl('settings_new'));
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
