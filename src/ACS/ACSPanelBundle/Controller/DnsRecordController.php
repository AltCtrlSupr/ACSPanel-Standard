<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

use ACS\ACSPanelBundle\Entity\DnsRecord;
use ACS\ACSPanelBundle\Entity\DnsDomain;
use ACS\ACSPanelBundle\Form\DnsRecordType;

use ACS\ACSPanelBundle\Event\FilterDnsEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

use ACS\ACSPanelBundle\Event\DnsEvents;

/**
 * DnsRecord controller.
 *
 * @Rest\RouteResource("DnsRecord")
 */
class DnsRecordController extends FOSRestController
{
    /**
     * Finds and displays a DnsRecord entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:DnsRecord')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DnsRecord entity.');
        }

        if (!$entity->userCanSee($this->get('security.context'))) {
            throw new \Exception('You cannot edit this entity!');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:DnsRecord:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    public function showWidgetAction($records)
    {
        return $this->render('ACSACSPanelBundle:DnsRecord:show_widget.html.twig', array(
            'records'      => $records,
        ));
    }

    /**
     * Displays a form to create a new DnsRecord entity.
     *
     */
    public function newAction($dnsdomain_id = '')
    {
        $entity = new DnsRecord();
        $entity->setTtl('3600');
        $entity->setType('A');
        if($dnsdomain_id != ''){
            $em = $this->getDoctrine()->getManager();
            $entity->setDnsDomain($em->getRepository('ACSACSPanelBundle:DnsDomain')->find($dnsdomain_id));
        }
        $form   = $this->createForm(new DnsRecordType($this->container), $entity);

        return $this->render('ACSACSPanelBundle:DnsRecord:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Creates new DNS Record",
     * )
     *
     * @Rest\Post("/dnsrecords/create")
     * @Rest\View("ACSACSPanelBundle:DnsRecord:new.html.twig", templateVar="entity")

     */
    public function createAction(Request $request)
    {
        $entity  = new DnsRecord();
        $form = $this->createForm(new DnsRecordType($this->container), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->container->get('event_dispatcher')->dispatch(DnsEvents::DNS_AFTER_RECORD_ADD, new FilterDnsEvent($entity,$em));

            $view = $this->routeRedirectView('dnsrecord_show', array('id' => $entity->getId()), 201);
            return $this->handleView($view);
        }

        return $form;
    }

    /**
     * Displays a form to edit an existing DnsRecord entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:DnsRecord')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DnsRecord entity.');
        }

        $editForm = $this->createForm(new DnsRecordType($this->container), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:DnsRecord:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing DnsRecord entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:DnsRecord')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DnsRecord entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DnsRecordType($this->container), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->container->get('event_dispatcher')->dispatch(DnsEvents::DNS_AFTER_RECORD_UPDATE, new FilterDnsEvent($entity,$em));

            return $this->redirect($this->generateUrl('dnsdomain_show', array('id' => $entity->getDnsDomain()->getId())));
        }

        return $this->render('ACSACSPanelBundle:DnsRecord:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a DnsRecord entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:DnsRecord')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DnsRecord entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->container->get('event_dispatcher')->dispatch(DnsEvents::DNS_AFTER_RECORD_DELETE, new FilterDnsEvent($entity,$em));
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
}
