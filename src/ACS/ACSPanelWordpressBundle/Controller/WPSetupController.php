<?php

namespace ACS\ACSPanelWordpressBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelWordpressBundle\Entity\WPSetup;
use ACS\ACSPanelWordpressBundle\Form\WPSetupType;

/**
 * WPSetup controller.
 *
 */
class WPSetupController extends Controller
{
    /**
     * Lists all WPSetup entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ACSACSPanelWordpressBundle:WPSetup')->findAll();

        $paginator  = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/
        );

        return $this->render('ACSACSPanelWordpressBundle:WPSetup:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a WPSetup entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelWordpressBundle:WPSetup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WPSetup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelWordpressBundle:WPSetup:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new WPSetup entity.
     *
     */
    public function newAction()
    {
        $entity = new WPSetup();
        $form   = $this->createForm(new WPSetupType($this->container), $entity);

        return $this->render('ACSACSPanelWordpressBundle:WPSetup:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new WPSetup entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new WPSetup();
        $form = $this->createForm(new WPSetupType($this->container), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('wpsetup_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelWordpressBundle:WPSetup:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing WPSetup entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelWordpressBundle:WPSetup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WPSetup entity.');
        }

        $editForm = $this->createForm(new WPSetupType($this->container), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelWordpressBundle:WPSetup:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing WPSetup entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelWordpressBundle:WPSetup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WPSetup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new WPSetupType($this->container), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('wpsetup_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelWordpressBundle:WPSetup:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a WPSetup entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelWordpressBundle:WPSetup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find WPSetup entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('wpsetup'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
