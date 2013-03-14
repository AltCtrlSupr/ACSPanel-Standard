<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\Database;
use ACS\ACSPanelBundle\Form\DatabaseType;

/**
 * Database controller.
 *
 */
class DatabaseController extends Controller
{
    /**
     * Lists all Database entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ACSACSPanelBundle:Database')->findAll();

        return $this->render('ACSACSPanelBundle:Database:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Database entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:Database')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Database entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:Database:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Database entity.
     *
     */
    public function newAction()
    {
        $entity = new Database();
        $form   = $this->createForm(new DatabaseType(), $entity);

        return $this->render('ACSACSPanelBundle:Database:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Database entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Database();
        $form = $this->createForm(new DatabaseType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('database_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:Database:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Database entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:Database')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Database entity.');
        }

        $editForm = $this->createForm(new DatabaseType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:Database:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Database entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:Database')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Database entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DatabaseType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('database_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:Database:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Database entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:Database')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Database entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('database'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
