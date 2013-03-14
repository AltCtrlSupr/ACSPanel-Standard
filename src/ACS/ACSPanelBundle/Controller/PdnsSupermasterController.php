<?php

namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\PdnsSupermaster;
use ACS\ACSPanelBundle\Form\PdnsSupermasterType;

/**
 * PdnsSupermaster controller.
 *
 */
class PdnsSupermasterController extends Controller
{
    /**
     * Lists all PdnsSupermaster entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ACSACSPanelBundle:PdnsSupermaster')->findAll();

        return $this->render('ACSACSPanelBundle:PdnsSupermaster:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a PdnsSupermaster entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:PdnsSupermaster')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PdnsSupermaster entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:PdnsSupermaster:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new PdnsSupermaster entity.
     *
     */
    public function newAction()
    {

        $entity = new PdnsSupermaster();
        $form   = $this->createForm(new PdnsSupermasterType(), $entity);

        return $this->render('ACSACSPanelBundle:PdnsSupermaster:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new PdnsSupermaster entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new PdnsSupermaster();
        $form = $this->createForm(new PdnsSupermasterType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('pdnssupermaster_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:PdnsSupermaster:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing PdnsSupermaster entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:PdnsSupermaster')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PdnsSupermaster entity.');
        }

        $editForm = $this->createForm(new PdnsSupermasterType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:PdnsSupermaster:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing PdnsSupermaster entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:PdnsSupermaster')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PdnsSupermaster entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new PdnsSupermasterType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('pdnssupermaster_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:PdnsSupermaster:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a PdnsSupermaster entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:PdnsSupermaster')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PdnsSupermaster entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('pdnssupermaster'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
