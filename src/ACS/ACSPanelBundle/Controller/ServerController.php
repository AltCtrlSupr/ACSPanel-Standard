<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\Server;
use ACS\ACSPanelBundle\Form\ServerType;

/**
 * Server controller.
 *
 */
class ServerController extends Controller
{
    /**
     * Lists all Server entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ACSACSPanelBundle:Server')->findAll();

        return $this->render('ACSACSPanelBundle:Server:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Server entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:Server')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Server entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:Server:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Server entity.
     *
     */
    public function newAction()
    {
        $entity = new Server();
        $form   = $this->createForm(new ServerType(), $entity);

        return $this->render('ACSACSPanelBundle:Server:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Server entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Server();
        $form = $this->createForm(new ServerType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setEnabled(true);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('server_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:Server:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Server entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:Server')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Server entity.');
        }

        $editForm = $this->createForm(new ServerType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:Server:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Server entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:Server')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Server entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ServerType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('server_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:Server:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Server entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:Server')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Server entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('server'));
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
      $entity = $em->getRepository('ACSACSPanelBundle:Server')->find($id);

      if (!$entity) {
         throw $this->createNotFoundException('Unable to find Server entity.');
      }

      $entity->setEnabled(!$entity->getEnabled());
      $em->persist($entity);
      $em->flush();

      return $this->redirect($this->generateUrl('server'));
    }

}
