<?php

namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

use ACS\ACSPanelBundle\Entity\DatabaseUser;
use ACS\ACSPanelBundle\Form\DatabaseUserType;

/**
 *
 * DatabaseUser controller.
 *
 * @Rest\RouteResource("DatabaseUser")
 */
class DatabaseUserController extends FOSRestController
{
    /**
     * Finds and displays a DatabaseUser entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:DatabaseUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DatabaseUser entity.');
        }

        if (!$entity->userCanSee($this->get('security.context'))) {
            throw new \Exception('You cannot edit this entity!');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:DatabaseUser:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new DatabaseUser entity.
     *
     */
    public function newAction()
    {
        $entity = new DatabaseUser();
        $form   = $this->createForm(new DatabaseUserType(), $entity);

        return $this->render('ACSACSPanelBundle:DatabaseUser:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new DatabaseUser entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new DatabaseUser();
        $form = $this->createForm(new DatabaseUserType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('databaseuser_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:DatabaseUser:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing DatabaseUser entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:DatabaseUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DatabaseUser entity.');
        }

        $editForm = $this->createForm(new DatabaseUserType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:DatabaseUser:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing DatabaseUser entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:DatabaseUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DatabaseUser entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DatabaseUserType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('databaseuser_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:DatabaseUser:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a DatabaseUser entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:DatabaseUser')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DatabaseUser entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('databaseuser'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
