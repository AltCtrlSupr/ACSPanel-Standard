<?php

namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\MailWBList;
use ACS\ACSPanelBundle\Form\MailWBListType;

/**
 * MailWBList controller.
 *
 */
class MailWBListController extends Controller
{
    /**
     * Lists all MailWBList entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ACSACSPanelBundle:MailWBList')->findAll();

        return $this->render('ACSACSPanelBundle:MailWBList:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a MailWBList entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:MailWBList')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailWBList entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:MailWBList:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new MailWBList entity.
     *
     */
    public function newAction($sender='',$rcpt='',$blacklisted='')
    {
        $entity = new MailWBList();
		  if($sender != ''){ $entity->setSender($sender); }
		  if($rcpt != ''){ $entity->setRcpt($rcpt); }
		  if($blacklisted==1){ 
		  		$entity->setBlacklisted(true); 
				$entity->setReject('Rejected by user');
		  }

        $form   = $this->createForm(new MailWBListType(), $entity);

        return $this->render('ACSACSPanelBundle:MailWBList:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new MailWBList entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new MailWBList();
        $form = $this->createForm(new MailWBListType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
				$entity->setEnabled(true);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mailwblist_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:MailWBList:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing MailWBList entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:MailWBList')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailWBList entity.');
        }

        $editForm = $this->createForm(new MailWBListType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:MailWBList:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing MailWBList entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:MailWBList')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailWBList entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new MailWBListType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mailwblist_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:MailWBList:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a MailWBList entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:MailWBList')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MailWBList entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('mailwblist'));
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
      $entity = $em->getRepository('ACSACSPanelBundle:MailWBList')->find($id);

      if (!$entity) {
         throw $this->createNotFoundException('Unable to find Mail WB List entity.');
      }

      $entity->setEnabled(!$entity->getEnabled());
      $em->persist($entity);
      $em->flush();

      return $this->redirect($this->generateUrl('mailwblist'));
    }

    public function setblacklistedAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('ACSACSPanelBundle:MailWBList')->find($id);

      if (!$entity) {
         throw $this->createNotFoundException('Unable to find Mail WB List entity.');
      }

      $entity->setBlacklisted(!$entity->getBlacklisted());
		if($entity->getReject()==''){
			$entity->setReject('Rejected by user');
		}
		if(!$entity->getBlacklisted()){
			$entity->setReject('');
		}
      $em->persist($entity);
      $em->flush();

      return $this->redirect($this->generateUrl('mailwblist'));
    }


}
