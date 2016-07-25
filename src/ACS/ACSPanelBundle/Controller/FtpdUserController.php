<?php

namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

use ACS\ACSPanelBundle\Entity\FtpdUser;
use ACS\ACSPanelBundle\Form\FtpdUserType;

/**
 * FtpdUser controller.
 *
 * @Rest\RouteResource("FtpdUser")
 */
class FtpdUserController extends FOSRestController
{
    /**
     * Lists all FtpdUser entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // IF is admin can see all the ftpdusers, if is user only their ones...
        $entities = $this->get('ftpduser_repository')->getUserViewable($this->get('security.token_storage')->getToken()->getUser());

        $entities = $em
            ->getRepository('ACSACSPanelBundle:FtpdUser')
            ->findBy(array(
                'user' => $this->get('security.token_storage')->getToken()->getUser()->getIdChildIds()
            )
        );

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

        if (!$entity->userCanSee($this->get('security.context'))) {
            throw new \Exception('You cannot edit this entity!');
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
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if (!$user->canUseResource('FtpdUser',$em)) {
            return $this->render('ACSACSPanelBundle:Error:resources.html.twig', array(
                'entity' => 'FtpdUser'
            ));
        }

        $entity = new FtpdUser();
        $form   = $this->createForm(new FtpdUserType($this->container), $entity);

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
        $form = $this->createForm(new FtpdUserType($this->container), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ($form->get("plain_password")) {
                $entity->setPassword($form->get("plain_password")->getData());
            }

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

        $editForm = $this->createForm(new FtpdUserType($this->container), $entity);
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
        $editForm = $this->createForm(new FtpdUserType($this->container), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            if ($editForm->get('plain_password')->getData() !== null) {
                $entity->setPassword($editForm->get('plain_password')->getData());
            }

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
