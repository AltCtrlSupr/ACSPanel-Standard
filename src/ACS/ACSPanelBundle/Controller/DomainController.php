<?php

namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\Domain;
use ACS\ACSPanelBundle\Form\DomainType;
use ACS\ACSPanelBundle\Event\DnsEvents;
use ACS\ACSPanelBundle\Event\FilterDnsEvent;

/**
 * Domain controller.
 *
 */
class DomainController extends Controller
{
    /**
     * Lists all Domain entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ACSACSPanelBundle:Domain')->findBy(array('user'=>$this->get('security.context')->getToken()->getUser()->getIdChildIds(),'parent_domain'=>NULL));

        return $this->render('ACSACSPanelBundle:Domain:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Domain entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:Domain')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Domain entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:Domain:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Domain entity.
     *
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$user->canUseResource('Domain',$em)) {
            throw new \Exception('You don\'t have enough resources!');
        }

        $entity = new Domain();
        $form   = $this->createForm(new DomainType(), $entity);

        return $this->render('ACSACSPanelBundle:Domain:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Domain entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Domain();
        $form = $this->createForm(new DomainType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
				$entity->setEnabled(true);

				#$this->container->get('event_dispatcher')->dispatch(DnsEvents::DOMAIN_BEFORE_ADD, new FilterDnsEvent($entity,$em));

            $em->persist($entity);
            $em->flush();


            return $this->redirect($this->generateUrl('domain_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:Domain:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Domain entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:Domain')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Domain entity.');
        }

        $editForm = $this->createForm(new DomainType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:Domain:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Domain entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:Domain')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Domain entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DomainType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('domain_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:Domain:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Domain entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:Domain')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Domain entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('domain'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

	 public function setaliasAction(Request $request, $id, $type)
	 {
	 	$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ACSACSPanelBundle:Domain')->find($id);

      if (!$entity) {
      	throw $this->createNotFoundException('Unable to find Domain entity.');
      }

		switch($type){
			case 'dns':
				$entity->setIsDnsAlias(!$entity->getIsDnsAlias());
				break;
			case 'httpd':
				$entity->setIsHttpdAlias(!$entity->getIsHttpdAlias());
				break;
			case 'mail':
				$entity->setIsMailAlias(!$entity->getIsMailAlias());
				break;
			default:
				throw $this->createException('Type not valid');
				break;
		}

		$em->persist($entity);
		$em->flush();

		return $this->redirect($this->generateUrl('domain'));
	 }

	 public function setenabledAction(Request $request, $id)
	 {
	 	$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ACSACSPanelBundle:Domain')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Domain entity.');
		}

		$entity->setEnabled(!$entity->getEnabled());
		$em->persist($entity);
		$em->flush();

		return $this->redirect($this->generateUrl('domain'));
	 }
}
