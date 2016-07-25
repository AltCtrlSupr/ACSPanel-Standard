<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

use ACS\ACSPanelBundle\Entity\HttpdUser;
use ACS\ACSPanelBundle\Form\UserHttpdUserType;

/**
 * HttpdUser controller.
 *
 * @Rest\RouteResource("HttpdUser")
 */
class HttpdUserController extends FOSRestController
{
    /**
     * Lists all HttpdUser entities.
     *
     * @Rest\View()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $this->get('httpduser_repository')->getUserViewable($this->get('security.token_storage')->getToken()->getUser());

        return array(
            'entities' => $entities
        );
    }

    /**
     * Finds and displays a HttpdUser entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:HttpdUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HttpdUser entity.');
        }

        if (!$entity->userCanSee($this->get('security.context'))) {
            throw new \Exception('You cannot edit this entity!');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:HttpdUser:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new HttpdUser entity.
     *
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if (!$user->canUseResource('HttpdUser',$em)) {
            return $this->render('ACSACSPanelBundle:Error:resources.html.twig', array(
                'entity' => 'HttpdUser'
            ));
        }

        $entity = new HttpdUser();
        $form   = $this->createForm(new UserHttpdUserType($this->container), $entity);

        return $this->render('ACSACSPanelBundle:HttpdUser:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new HttpdUser entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new HttpdUser();
        $form = $this->createForm(new UserHttpdUserType($this->container), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setEnabled(true);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('httpduser_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:HttpdUser:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing HttpdUser entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:HttpdUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HttpdUser entity.');
        }

        $editForm = $this->createForm(new UserHttpdUserType($this->container), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:HttpdUser:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing HttpdUser entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:HttpdUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HttpdUser entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new UserHttpdUserType($this->container), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            // Mark webserver to restart
            $this->get('server.actions')->setWebserverToReload($entity->getHttpdHost()->getService()->getServer());
            $em->flush();

            return $this->redirect($this->generateUrl('httpduser_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:HttpdUser:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a HttpdUser entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:HttpdUser')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find HttpdUser entity.');
            }

            $em->remove($entity);
            // Mark webserver to restart
            //$this->get('server.actions')->setWebserverToReload($entity->getHttpdHost()->getService()->getServer());
            $em->flush();
        }

        return $this->redirect($this->generateUrl('httpduser'));
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
        $entity = $em->getRepository('ACSACSPanelBundle:HttpdUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find htttpd user entity.');
        }

        $entity->setEnabled(!$entity->getEnabled());
        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('httpduser'));
    }
}
