<?php

namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

use ACS\ACSPanelBundle\Entity\DnsRecord;
use ACS\ACSPanelBundle\Entity\MailDomain;
use ACS\ACSPanelBundle\Form\MailDomainType;


use ACS\ACSPanelBundle\Event\FilterDnsEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

use ACS\ACSPanelBundle\Event\DnsEvents;

/**
 * MailDomain controller.
 *
 * @Rest\RouteResource("MailDomain")
 */
class MailDomainController extends FOSRestController
{
    /**
     * Lists all MailDomain entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // IF is admin can see all the hosts, if is user only their ones...
        $entities = $this->get('maildomain_repository')->getUserViewable($this->get('security.token_storage')->getToken()->getUser());

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

        if (!$entity->userCanSee($this->get('security.context'))) {
            throw new \Exception('You cannot edit this entity!');
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
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if (!$user->canUseResource('MailDomain',$em)) {
            return $this->render('ACSACSPanelBundle:Error:resources.html.twig', array(
                'entity' => 'MailDomain'
            ));
        }

        $entity = new MailDomain();
        $form   = $this->createForm(new MailDomainType($this->container), $entity);

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
        $form = $this->createForm(new MailDomainType($this->container), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setEnabled(true);
            $em->persist($entity);
            $em->flush();

            // Add the dns record if user requested
            if($form['add_dns_record']->getData()){
                $dnsrecord = new DnsRecord();
                $dnsdomain = $em->getRepository('ACSACSPanelBundle:DnsDomain')->findOneByDomain($entity->getDomain());
                $dnsrecord->setDnsDomain($dnsdomain);
                $dnsrecord->setName('mail.'.$entity->getDomain()->getDomain());
                $dnsrecord->setType('A');
                $dnsrecord->setContent($entity->getService()->getIp()->getIp());
                $em->persist($dnsrecord);

                $mxdnsrecord = new DnsRecord();
                $mxdnsrecord->setDnsDomain($dnsdomain);
                $mxdnsrecord->setName($entity->getDomain()->getDomain());
                $mxdnsrecord->setType('MX');
                $mxdnsrecord->setContent($dnsrecord->getName());
                $em->persist($mxdnsrecord);

                $em->flush();

                $this->container->get('event_dispatcher')->dispatch(DnsEvents::DNS_AFTER_RECORD_ADD, new FilterDnsEvent($dnsrecord, $em));
            }


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

        $editForm = $this->createForm(new MailDomainType($this->container), $entity);
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
        $editForm = $this->createForm(new MailDomainType($this->container), $entity);
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
