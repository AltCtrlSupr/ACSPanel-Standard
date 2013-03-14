<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\HttpdUser;
use ACS\ACSPanelBundle\Form\UserHttpdUserType;

/**
 * HttpdUser controller.
 * @todo: Check if it's necessary to mark webserver to restart
 *
 */
class HttpdUserController extends Controller

{
    /**
     * Lists all HttpdUser entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ACSACSPanelBundle:HttpdUser')->findAll();

        return $this->render('ACSACSPanelBundle:HttpdUser:index.html.twig', array(
            'entities' => $entities,
        ));
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
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$user->canUseResource('HttpdUser',$em)) {
            throw new \Exception('You don\'t have enough resources!');
        }

        $entity = new HttpdUser();
        $form   = $this->createForm(new UserHttpdUserType(), $entity);

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
        $form = $this->createForm(new UserHttpdUserType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
				$entity->setEnabled(true);
            $em->persist($entity);
            $em->flush();

            // Mark webserver to restart
            //$this->get('server.actions')->setWebserverToReload($entity->getHttpdHost()->getService()->getServer());

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

        $editForm = $this->createForm(new UserHttpdUserType(), $entity);
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
        $editForm = $this->createForm(new UserHttpdUserType(), $entity);
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
