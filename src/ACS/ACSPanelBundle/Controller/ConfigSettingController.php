<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelSettingsBundle\Entity\ConfigSetting;
use ACS\ACSPanelSettingsBundle\Form\ConfigSettingType;

use ACS\ACSPanelSettingsBundle\Controller\ConfigSettingController as BaseController;

/**
 * ConfigSetting controller.
 *
 */
class ConfigSettingController extends BaseController
{
    /**
     * Lists all ConfigSetting entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $this->get('acs.setting_manager')->findBy(array('focus' => 'internal'));

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
        $class_name = $this->container->getParameter('settings_setting_class');
        $entity = new $class_name;
        $form   = $this->createForm(new ConfigSettingType(), $entity);

        return $this->render('ACSACSPanelSettingsBundle:ConfigSetting:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new ConfigSetting entity.
     *
     */
    public function createAction(Request $request)
    {
        $class_name = $this->container->getParameter('settings_setting_class');
        $entity = new $class_name;
        $form = $this->createForm(new ConfigSettingType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('settings_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelSettingsBundle:ConfigSetting:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ConfigSetting entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('acs.setting_manager')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ConfigSetting entity.');
        }

        $editForm = $this->createForm(new ConfigSettingType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelSettingsBundle:ConfigSetting:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing ConfigSetting entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('acs.setting_manager')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ConfigSetting entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ConfigSettingType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('settings_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelSettingsBundle:ConfigSetting:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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
