<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\MailDomain;
use ACS\ACSPanelBundle\Form\MailDomainType;

/**
 * MailDomain controller.
 *
 */
class MailDomainController extends Controller
{
    /**
     * Lists all MailDomain entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // IF is admin can see all the hosts, if is user only their ones...
        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $entities = $em->getRepository('ACSACSPanelBundle:MailDomain')->findAll();
        }elseif(true === $this->get('security.context')->isGranted('ROLE_RESELLER')){
            $entities = $em->getRepository('ACSACSPanelBundle:MailDomain')->findByUsers($this->get('security.context')->getToken()->getUser()->getIdChildIds());
        }elseif(true === $this->get('security.context')->isGranted('ROLE_USER')){
            $entities = $em->getRepository('ACSACSPanelBundle:MailDomain')->findByUser($this->get('security.context')->getToken()->getUser());
        }

        return $this->render('ACSACSPanelBundle:MailDomain:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a MailDomain entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:MailDomain')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailDomain entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:MailDomain:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new MailDomain entity.
     *
     */
    public function newAction()
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$user->canUseResource('MailDomain',$em)) {
            throw new \Exception('You don\'t have enough resources!');
        }

        $entity = new MailDomain();
        $form   = $this->createForm(new MailDomainType(), $entity);

        return $this->render('ACSACSPanelBundle:MailDomain:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new MailDomain entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new MailDomain();
        $form = $this->createForm(new MailDomainType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setEnabled(true);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('maildomain_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:MailDomain:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing MailDomain entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:MailDomain')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailDomain entity.');
        }

        $editForm = $this->createForm(new MailDomainType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:MailDomain:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing MailDomain entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:MailDomain')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailDomain entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new MailDomainType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('maildomain_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:MailDomain:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a MailDomain entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:MailDomain')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MailDomain entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('maildomain'));
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
      $entity = $em->getRepository('ACSACSPanelBundle:MailDomain')->find($id);

      if (!$entity) {
         throw $this->createNotFoundException('Unable to find Mail Domain entity.');
      }

      $entity->setEnabled(!$entity->getEnabled());
      $em->persist($entity);
      $em->flush();

      return $this->redirect($this->generateUrl('maildomain'));
    }

}
