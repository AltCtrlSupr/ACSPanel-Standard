<?php

namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

use ACS\ACSPanelBundle\Entity\IpAddress;
use ACS\ACSPanelBundle\Form\IpAddressType;

/**
 * IpAddress controller.
 *
 * @Rest\RouteResource("IpAddress")
 */
class IpAddressController extends FOSRestController
{
    /**
     * Lists all IpAddress entities.
     *
     * @Rest\View(templateVar="search_action")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $this->get('ipaddress_repository')->getUserViewable($this->get('security.context')->getToken()->getUser());

        return array(
            'entities' => $entities
        );
    }

    /**
     * Finds and displays a IpAddress entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:IpAddress')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find IpAddress entity.');
        }

        if (!$entity->userCanSee($this->get('security.context'))) {
            throw new \Exception('You cannot edit this entity!');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:IpAddress:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new IpAddress entity.
     *
     */
    public function newAction()
    {
        $entity = new IpAddress();
        $form   = $this->createForm(new IpAddressType($this->container), $entity);

        return $this->render('ACSACSPanelBundle:IpAddress:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new IpAddress entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new IpAddress();
        $form = $this->createForm(new IpAddressType($this->container), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setEnabled(true);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ipaddress_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:IpAddress:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing IpAddress entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:IpAddress')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find IpAddress entity.');
        }

        $editForm = $this->createForm(new IpAddressType($this->container), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:IpAddress:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing IpAddress entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:IpAddress')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find IpAddress entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new IpAddressType($this->container), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ipaddress_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:IpAddress:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a IpAddress entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:IpAddress')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find IpAddress entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ipaddress'));
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
      $entity = $em->getRepository('ACSACSPanelBundle:IpAddress')->find($id);

      if (!$entity) {
         throw $this->createNotFoundException('Unable to find IP Address entity.');
      }

      $entity->setEnabled(!$entity->getEnabled());
      $em->persist($entity);
      $em->flush();

      return $this->redirect($this->generateUrl('ipaddress'));
    }

}
