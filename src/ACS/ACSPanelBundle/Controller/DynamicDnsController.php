<?php

namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

use ACS\ACSPanelBundle\Entity\DnsRecord;
use ACS\ACSPanelBundle\Entity\DnsDomain;
use ACS\ACSPanelBundle\Form\DynDnsRecordType;

use ACS\ACSPanelBundle\Event\FilterDnsEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

use ACS\ACSPanelBundle\Event\DnsEvents;

/**
 * DynamicDNS controller.
 *
 */
class DynamicDnsController extends FOSRestController
{
    /**
     * Updates DNSRecord IP
     *
     * @Rest\View()
     */
    public function updateAction(Request $request)
    {
        $new_ip = $request->get('myip');
        $hostname = $request->get('hostname');

        if (!$new_ip) {
            $new_ip = $this->getIpFromRequest($request);
        }

        $record = $this->getRecordToUpdate($hostname);

        if ($record && $new_ip && $hostname) {
            $record->setContent($new_ip);

            $em = $this->getDoctrine()->getManager();
            $em->persist($record);
            $em->flush();

            $this->container->get('event_dispatcher')->dispatch(DnsEvents::DNS_AFTER_RECORD_UPDATE, new FilterDnsEvent($record, $em));

            $view = $this->view([], 200)
                ->setFormat('json')
            ;

            return $view;
        }

        throw $this->createNotFoundException('You need to provide the required parameters');
    }

    /**
     * getIpFromRequest
     *
     * @param Request $request
     * @access private
     * @return void
     */
    private function getIpFromRequest(Request $request)
    {
        $new_ip = $request->getClientIp();
        return $new_ip;
    }

    /**
     * Displays a form to create a new (dynamic)DNSRecord entity.
     *
     * @Rest\View()
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if (!$user->canUseResource('DnsRecord',$em)) {
            return $this->render('ACSACSPanelBundle:Error:resources.html.twig', array(
                'entity' => 'DnsRecord'
            ));
        }

        $entity = new DnsRecord();
        $form   = $this->createForm(DynDnsRecordType::class, $entity, [
            'token_storage' => $this->get('security.token_storage'),
            'authorization_checker' => $this->get('security.authorization_checker'),
        ]);

        $tplData = array(
            'entity' => $entity,
            'form'   => $form->createView()
        );

        $view = $this->view($tplData, 200)
            ->setTemplate('ACSACSPanelBundle:DynamicDns:new.html.twig')
        ;

        return $view;
    }

    /**
     * @Rest\View()
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity  = new DnsRecord();
        $form = $this->createForm(DynDnsRecordType::class, $entity, [
            'token_storage' => $this->get('security.token_storage'),
            'authorization_checker' => $this->get('security.authorization_checker'),
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setName($request->get('subdomain') . $entity->getDnsDomain()->getDomain());
            $entity->setType('A');
            $em->persist($entity);

            $em->flush();

            // It only works with 201 code
            $view = $this->routeRedirectView('dyndns_show', array('name' => $entity->getName()), 201);
            return $this->handleView($view);
        }

        $view = $this->view($entity, 200)
            ->setTemplateData(array('form' => $form->createView()))
        ;

        return $view;

    }

    /**
     * Shows your record
     *
     * @Rest\Get("/dyndnsrecord/{name}/show")
     * @Rest\View(templateVar="entity")
     */
    public function showAction($name)
    {
        $record = $this->getRecordToUpdate($name);
        $view = $this->view($record, 200);

        return $view;
    }

    /**
     * getRecordToUpdate
     *
     * @param mixed $hostname
     * @access private
     * @return void
     */
    private function getRecordToUpdate($hostname)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('dnsrecord_repository')->findOneDynamic($this->get('security.token_storage')->getToken()->getUser(), $hostname);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DnsRecord entity.');
        }

        return $entity;
    }
}
