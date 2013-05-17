<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\DnsDomain;
use ACS\ACSPanelBundle\Entity\DnsRecord;
use ACS\ACSPanelBundle\Form\DnsDomainType;

use ACS\ACSPanelBundle\Event\FilterDnsEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

use ACS\ACSPanelBundle\Event\DnsEvents;


/**
 *  * DnsDomain controller.
 *   *
 *    */
class DnsDomainController extends Controller
{
    /**
     ** Lists all DnsDomain entities.
     **
     **/
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // IF is admin can see all the dnsdomains, if is user only their ones...
        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $entities = $em->getRepository('ACSACSPanelBundle:DnsDomain')->findAll();
        }elseif(true === $this->get('security.context')->isGranted('ROLE_RESELLER')){
            $entities = $em->getRepository('ACSACSPanelBundle:DnsDomain')->findByUsers($this->get('security.context')->getToken()->getUser()->getIdChildIds());
        }elseif(true === $this->get('security.context')->isGranted('ROLE_USER')){
            $entities = $em->getRepository('ACSACSPanelBundle:DnsDomain')->findByUser($this->get('security.context')->getToken()->getUser());
        }


        $paginator  = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/
        );

        return $this->render('ACSACSPanelBundle:DnsDomain:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     ** Finds and displays a DnsDomain entity.
     **
     **/
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:DnsDomain')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DnsDomain entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:DnsDomain:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     ** Displays a form to create a new DnsDomain entity.
     **
     **/
    public function newAction()
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$user->canUseResource('DnsDomain',$em)) {
            throw new \Exception('You don\'t have enough resources!');
        }

        $entity = new DnsDomain();
        $form   = $this->createForm(new DnsDomainType(), $entity);

        return $this->render('ACSACSPanelBundle:DnsDomain:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     ** Creates a new DnsDomain entity.
     **
     **/
    public function createAction(Request $request)
    {
        $entity  = new DnsDomain();
        $form = $this->createForm(new DnsDomainType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setEnabled(true);
            $em->persist($entity);
            $em->flush();

            $this->container->get('event_dispatcher')->dispatch(DnsEvents::DNS_AFTER_DOMAIN_ADD, new FilterDnsEvent($entity,$em));

            return $this->redirect($this->generateUrl('dnsdomain_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:DnsDomain:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     ** Displays a form to edit an existing DnsDomain entity.
     **
     **/
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:DnsDomain')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DnsDomain entity.');
        }

        $editForm = $this->createForm(new DnsDomainType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:DnsDomain:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     ** Edits an existing DnsDomain entity.
     **
     **/
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:DnsDomain')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DnsDomain entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DnsDomainType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->container->get('event_dispatcher')->dispatch(DnsEvents::DNS_AFTER_DOMAIN_UPDATE, new FilterDnsEvent($entity,$em));

            return $this->redirect($this->generateUrl('dnsdomain_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:DnsDomain:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     ** Deletes a DnsDomain entity.
     **
     **/
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:DnsDomain')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DnsDomain entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('dnsdomain'));
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
      $entity = $em->getRepository('ACSACSPanelBundle:DnsDomain')->find($id);

      if (!$entity) {
         throw $this->createNotFoundException('Unable to find Dns Domain entity.');
      }

      $entity->setEnabled(!$entity->getEnabled());
      $em->persist($entity);
      $em->flush();

      return $this->redirect($this->generateUrl('dnsdomain'));
    }

}
