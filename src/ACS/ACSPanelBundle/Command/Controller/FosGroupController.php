<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\FosGroup;
use ACS\ACSPanelBundle\Form\FosGroupType;

/**
 * FosGroup controller.
 *
 */
class FosGroupController extends Controller

{
    /**
     * Lists all FosGroup entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ACSACSPanelBundle:FosGroup')->findAll();

        return $this->render('ACSACSPanelBundle:FosGroup:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a FosGroup entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:FosGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FosGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:FosGroup:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new FosGroup entity.
     *
     */
    public function newAction()
    {
        $entity = new FosGroup();
        $form   = $this->createForm(new FosGroupType(), $entity);

        return $this->render('ACSACSPanelBundle:FosGroup:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new FosGroup entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new FosGroup();
        $form = $this->createForm(new FosGroupType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('groups_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:FosGroup:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing FosGroup entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:FosGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FosGroup entity.');
        }

        $editForm = $this->createForm(new FosGroupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:FosGroup:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing FosGroup entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:FosGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FosGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FosGroupType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('groups_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:FosGroup:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a FosGroup entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:FosGroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FosGroup entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('groups'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
