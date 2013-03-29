<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\FtpdUser;
use ACS\ACSPanelBundle\Form\FtpdUserType;

/**
 * FtpdUser controller.
 *
 */
class FtpdUserController extends Controller
{
    /**
     * Lists all FtpdUser entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ACSACSPanelBundle:FtpdUser')->findBy(array('user'=>$this->get('security.context')->getToken()->getUser()->getIdChildIds()));

        return $this->render('ACSACSPanelBundle:FtpdUser:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a FtpdUser entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:FtpdUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FtpdUser entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:FtpdUser:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new FtpdUser entity.
     *
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$user->canUseResource('FtpdUser',$em)) {
            throw new \Exception('You don\'t have enough resources!');
        }

        $entity = new FtpdUser();
        $form   = $this->createForm(new FtpdUserType(), $entity);

        return $this->render('ACSACSPanelBundle:FtpdUser:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new FtpdUser entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new FtpdUser();
        $form = $this->createForm(new FtpdUserType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ftpduser_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:FtpdUser:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing FtpdUser entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:FtpdUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FtpdUser entity.');
        }

        $editForm = $this->createForm(new FtpdUserType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:FtpdUser:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing FtpdUser entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:FtpdUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FtpdUser entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FtpdUserType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ftpduser_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:FtpdUser:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a FtpdUser entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:FtpdUser')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FtpdUser entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ftpduser'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
