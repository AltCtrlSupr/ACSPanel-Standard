<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\ServiceType;
use ACS\ACSPanelBundle\Form\ServiceTypeType;

/**
 * ServiceType controller.
 *
 */
class ServiceTypeController extends Controller
{
    /**
     * Lists all ServiceType entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $this->get('servicetype_repository')->getUserViewable($this->get('security.token_storage')->getToken()->getUser());

        return $this->render('ACSACSPanelBundle:ServiceType:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a ServiceType entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:ServiceType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ServiceType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:ServiceType:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new ServiceType entity.
     *
     */
    public function newAction()
    {
        $entity = new ServiceType();
        $form   = $this->createForm(new ServiceTypeType(), $entity);

        return $this->render('ACSACSPanelBundle:ServiceType:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new ServiceType entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new ServiceType();
        $form = $this->createForm(new ServiceTypeType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('servicetype_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:ServiceType:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ServiceType entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:ServiceType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ServiceType entity.');
        }

        $editForm = $this->createForm(new ServiceTypeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:ServiceType:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing ServiceType entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:ServiceType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ServiceType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ServiceTypeType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('servicetype_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:ServiceType:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ServiceType entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:ServiceType')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ServiceType entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('servicetype'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
