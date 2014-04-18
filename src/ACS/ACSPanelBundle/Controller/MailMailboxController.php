<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\MailMailbox;
use ACS\ACSPanelBundle\Form\MailMailboxType;

/**
 * MailMailbox controller.
 *
 */
class MailMailboxController extends Controller
{
    /**
     * Lists all MailMailbox entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // IF is admin can see all the hosts, if is user only their ones...
        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $entities = $em->getRepository('ACSACSPanelBundle:MailMailbox')->findAll();
        }elseif(true === $this->get('security.context')->isGranted('ROLE_RESELLER')){
            $entities = $em->getRepository('ACSACSPanelBundle:MailMailbox')->findByUsers($this->get('security.context')->getToken()->getUser()->getIdChildIds());
        }elseif(true === $this->get('security.context')->isGranted('ROLE_USER')){
            $entities = $em->getRepository('ACSACSPanelBundle:MailMailbox')->findByUser($this->get('security.context')->getToken()->getUser());
        }


        $paginator  = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/
        );

        return $this->render('ACSACSPanelBundle:MailMailbox:index.html.twig', array(
            'entities' => $entities,
            'search_action' => 'mailmailbox_search',
        ));
    }

    /**
     * Finds and displays a MailMailbox entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:MailMailbox')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailMailbox entity.');
        }

        if (!$entity->userCanSee($this->get('security.context'))) {
            throw new \Exception('You cannot edit this entity!');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:MailMailbox:show.html.twig', array(
            'entity'      => $entity,
            'search_action' => 'mailmailbox_search',
            'delete_form' => $deleteForm->createView(),        ));
    }

	 public function showWidgetAction($maildomain_id)
	 {
	 	$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ACSACSPanelBundle:MailMailbox')->findBy(array('mail_domain' => $maildomain_id));
		return $this->render('ACSACSPanelBundle:MailMailbox:show_widget.html.twig', array(
			'entities' => $entities,
		));
	 }

    /**
     * Displays a form to create a new MailMailbox entity.
     *
     */
    public function newAction($maildomain_id = '')
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$user->canUseResource('MailMailbox',$em)) {
            return $this->render('ACSACSPanelBundle:Error:resources.html.twig', array(
                'entity' => 'MailMailbox'
            ));
        }

        $entity = new MailMailbox();
        if($maildomain_id != ''){
            $entity->setDomain($em->getRepository('ACSACSPanelBundle:MailDomain')->find($maildomain_id));
        }
        $form   = $this->createForm(new MailMailboxType(), $entity);

        return $this->render('ACSACSPanelBundle:MailMailbox:new.html.twig', array(
            'entity' => $entity,
            'search_action' => 'mailmailbox_search',
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new MailMailbox entity.
     *
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity  = new MailMailbox();
        $form = $this->createForm(new MailMailboxType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
				// TODO: Delete from entity
		  		$entity->setMaildir('/home/Maildir');
				// TODO: Get from User Plan
				$entity->setQuota(1000);
				$entity->setQuotaLimit(1000);
				$entity->setUsedQuota(0);
				$entity->setBytes(0);
				$entity->setMessages(0);
            $entity->setEnabled(true);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mailmailbox_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:MailMailbox:new.html.twig', array(
            'entity' => $entity,
            'search_action' => 'mailmailbox_search',
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing MailMailbox entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:MailMailbox')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailMailbox entity.');
        }

        if (!$entity->userCanEdit($this->get('security.context')->getToken()->getUser())) {
            throw $this->createNotFoundException('You cannot edit this entry.');
        }


        $editForm = $this->createForm(new MailMailboxType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:MailMailbox:edit.html.twig', array(
            'entity'      => $entity,
            'search_action' => 'mailmailbox_search',
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing MailMailbox entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:MailMailbox')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailMailbox entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new MailMailboxType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mailmailbox_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:MailMailbox:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'search_action' => 'mailmailbox_search',
        ));
    }

    /**
     * Deletes a MailMailbox entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:MailMailbox')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MailMailbox entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('mailmailbox'));
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
      $entity = $em->getRepository('ACSACSPanelBundle:MailMailbox')->find($id);

      if (!$entity) {
         throw $this->createNotFoundException('Unable to find Mail Mailbox entity.');
      }

      $entity->setEnabled(!$entity->getEnabled());
      $em->persist($entity);
      $em->flush();

      return $this->redirect($this->generateUrl('mailmailbox'));
    }

    /**
     * Finds and displays MailMailbox search results.
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('ACSACSPanelBundle:MailMailbox');

        $term = $request->request->get('term');

        $query = $rep->createQueryBuilder('m')
            ->where('m.id = ?1')
            ->innerJoin('m.mail_domain','md')
	    ->innerJoin('md.domain','d')
            ->orWhere('d.domain LIKE ?2')
            ->orWhere('m.name LIKE ?2')
            ->orWhere('m.maildir LIKE ?2')
            ->setParameter('1',$term)
            ->setParameter('2','%'.$term.'%')
            ->getQuery();

        $entities = $query->execute();

        $paginator  = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/,
            6
        );

        return $this->render('ACSACSPanelBundle:MailMailbox:index.html.twig', array(
            'entities' => $entities,
            'term' => $term,
            'search_action' => 'mailmailbox_search',
        ));

    }


}
