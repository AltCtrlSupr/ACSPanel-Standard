<?php

namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

use ACS\ACSPanelBundle\Entity\DB;
use ACS\ACSPanelBundle\Entity\DatabaseUser;
use ACS\ACSPanelBundle\Form\DBType;

/**
 * DB controller.
 *
 * @Rest\RouteResource("DB")
 */
class DBController extends FOSRestController
{
    /**
     * Lists all DB entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // IF is admin can see all the databases, if is user only their ones...
        $entities = $this->get('db_repository')->getUserViewable($this->get('security.context')->getToken()->getUser());

        return $this->render('ACSACSPanelBundle:DB:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a DB entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:DB')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DB entity.');
        }

        if (!$entity->userCanSee($this->get('security.context'))) {
            throw new \Exception('You cannot see this entity!');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:DB:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new DB entity.
     *
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        if (!$user->canUseResource('Db',$em)) {
            return $this->render('ACSACSPanelBundle:Error:resources.html.twig', array(
                'entity' => 'Db'
            ));
        }

        $entity = new DB();

        $form   = $this->createForm(new DBType($this->container), $entity);

        return $this->render('ACSACSPanelBundle:DB:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new DB entity.
     *
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity  = new DB();
        $form = $this->createForm(new DBType($this->container), $entity);
        $form->bind($request);

        $users = $entity->getDatabaseUsers();

        $errors = array();
        $validator = $this->get('validator');

        // We get current user, if is admin action we take the form user
        $user = $this->get('security.context')->getToken()->getUser();
        if($entity->getUser())
            $user = $entity->getUser();

        foreach($users as $dbuser){
            $dbuser->setUsername($user->getId().'_'.$dbuser->getUsername());
            $dbuser->setDb($entity);
            $errors = $validator->validate($dbuser);
        }

        if ($form->isValid() && !count($errors)) {

            $em = $this->getDoctrine()->getManager();

            $entity->setName($user->getUsername().'_'.$entity->getName());

            // foreach($users as $dbuser){
            // }
            //
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('db_show', array('id' => $entity->getId())));

        }

        return $this->render('ACSACSPanelBundle:DB:new.html.twig', array(
            'entity' => $entity,
            'errors' => $errors,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing DB entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:DB')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DB entity.');
        }

        $editForm = $this->createForm(new DBType($this->container), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:DB:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing DB entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:DB')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DB entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DBType($this->container), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('db_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:DB:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a DB entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:DB')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DB entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('db'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
