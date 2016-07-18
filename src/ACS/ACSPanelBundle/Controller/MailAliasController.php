<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

use ACS\ACSPanelBundle\Entity\MailAlias;
use ACS\ACSPanelBundle\Form\MailAliasType;

/**
 * MailAlias controller.
 *
 * @Rest\RouteResource("MailAlias")
 */
class MailAliasController extends FOSRestController
{
    /**
     * Lists all MailAlias entities.
     *
     * @Rest\View(templateVar="search_action")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // IF is admin can see all the hosts, if is user only their ones...
        $entities = $this->get('mailalias_repository')->getUserViewable($this->get('security.context')->getToken()->getUser());

        return array(
            'entities' => $entities
        );
    }

    /**
     * Finds and displays a MailAlias entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:MailAlias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailAlias entity.');
        }

        if (!$entity->userCanSee($this->get('security.context'))) {
            throw new \Exception('You cannot edit this entity!');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:MailAlias:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function showWidgetAction($maildomain_id)
    {
      $em = $this->getDoctrine()->getManager();
      $entities = $em->getRepository('ACSACSPanelBundle:MailAlias')->findBy(array('mail_domain'=>$maildomain_id));

      return $this->render('ACSACSPanelBundle:MailAlias:show_widget.html.twig', array(
         'entities' => $entities,
      ));
    }


    /**
     * Displays a form to create a new MailAlias entity.
     *
     */
    public function newAction($maildomain_id = '')
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$user->canUseResource('MailAlias',$em)) {
            return $this->render('ACSACSPanelBundle:Error:resources.html.twig', array(
                'entity' => 'MailAlias'
            ));
        }

        $entity = new MailAlias();

        if($maildomain_id != ''){
            $entity->setMailDomain($em->getRepository('ACSACSPanelBundle:MailDomain')->find($maildomain_id));
        }
        $form   = $this->createForm(new MailAliasType($this->container), $entity);

        return $this->render('ACSACSPanelBundle:MailAlias:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new MailAlias entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new MailAlias();
        $form = $this->createForm(new MailAliasType($this->container), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setEnabled(true);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mailalias_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:MailAlias:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing MailAlias entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:MailAlias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailAlias entity.');
        }

        $editForm = $this->createForm(new MailAliasType($this->container), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:MailAlias:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing MailAlias entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:MailAlias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailAlias entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new MailAliasType($this->container), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mailalias_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:MailAlias:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a MailAlias entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:MailAlias')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MailAlias entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('mailalias'));
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
      $entity = $em->getRepository('ACSACSPanelBundle:MailAlias')->find($id);

      if (!$entity) {
         throw $this->createNotFoundException('Unable to find Mail Alias entity.');
      }

      $entity->setEnabled(!$entity->getEnabled());
      $em->persist($entity);
      $em->flush();

      return $this->redirect($this->generateUrl('mailalias'));
    }

}
