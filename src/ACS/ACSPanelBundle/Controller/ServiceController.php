<?php

namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

use ACS\ACSPanelBundle\Entity\Service;
use ACS\ACSPanelBundle\Entity\PanelSetting;
use ACS\ACSPanelSettingsBundle\Form\ConfigSettingType;
use ACS\ACSPanelBundle\Form\ServiceType;

/**
 * Service controller.
 *
 * @Rest\RouteResource("Service")
 */
class ServiceController extends FOSRestController
{
    /**
     * Lists all Service entities.
     *
     * @Rest\View(templateVar="search_action")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $this->get('service_repository')->getUserViewable($this->get('security.context')->getToken()->getUser());

        return array(
            'entities' => $entities
        );
    }

    /**
     * Finds and displays a Service entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:Service')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Service entity.');
        }

        if (!$entity->userCanSee($this->get('security.context'))) {
            throw new \Exception('You cannot edit this entity!');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:Service:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Service entity.
     *
     */
    public function newAction()
    {
        $entity = new Service();
        $form   = $this->createForm(new ServiceType($this->container), $entity);

        return $this->render('ACSACSPanelBundle:Service:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Service entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Service();
        $form = $this->createForm(new ServiceType($this->container), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setEnabled(true);
            $em->persist($entity);
            $em->flush();

            /*$settingsform = $this->createFormBuilder()->add('settings', 'collection');
            $service_type = $entity->getType();

            foreach($service_type->getFieldTypes() as $field_type){
                $setting = new PanelSetting();
                $setting->setService($entity);
                $setting->setServer($entity->getServer());
                $setting->setSettingKey($field_type->getInternalName());
                $setting->setContext("internal");
                $form = new ConfigSettingType($setting);
                switch($field_type->getFieldType()){
                    case 'input':
                        $form->add('value', 'text');
                        break;
                    case 'text':
                        $form->add('value', 'text');
                        break;
                    case 'textarea':
                        $form->add('value', 'textarea');
                        break;
                    case 'password':
                        $form->add('value', 'password');
                        break;
                }
                //$form = $form->getFormFactory();
                $settingsform->get('settings')->add($field_type->getName(), $form);
            }
            $settingsform = $settingsform->getForm();
             */

            return $this->redirect($this->generateUrl('settings_object_settings', array('object_id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:Service:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Service entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:Service')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Service entity.');
        }

        $editForm = $this->createForm(new ServiceType($this->container), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:Service:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Service entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:Service')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Service entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ServiceType($this->container), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('service_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:Service:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Service entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:Service')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Service entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('service'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    public function setenabledAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('ACSACSPanelBundle:Service')->find($id);

      if (!$entity) {
         throw $this->createNotFoundException('Unable to find Service entity.');
      }

      $entity->setEnabled(!$entity->getEnabled());
      $em->persist($entity);
      $em->flush();

      return $this->redirect($this->generateUrl('service'));
    }

}
