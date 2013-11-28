<?php

namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\Domain;
use ACS\ACSPanelBundle\Entity\DnsDomain;
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

        // IF is admin can see all the hosts, if is user only their ones...
        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $entities = $em->getRepository('ACSACSPanelBundle:Domain')->findBy(array('parent_domain' => null));
        }elseif(true === $this->get('security.context')->isGranted('ROLE_RESELLER')){
            $entities = $em->getRepository('ACSACSPanelBundle:Domain')->findByUsers($this->get('security.context')->getToken()->getUser()->getIdChildIds());
        }elseif(true === $this->get('security.context')->isGranted('ROLE_USER')){
            $entities = $em->getRepository('ACSACSPanelBundle:Domain')->findByUser($this->get('security.context')->getToken()->getUser());
        }

        $paginator  = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/,
            6
        );

        return $this->render('ACSACSPanelBundle:Domain:index.html.twig', array(
            'entities' => $entities,
            'search_action' => 'domain_search',
        ));
    }

    /**
     * Finds and displays a LogItem search results.
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('ACSACSPanelBundle:Domain');

        $term = $request->request->get('term');

        $query = $rep->createQueryBuilder('d')
            ->where('d.id = ?1')
            ->orWhere('d.domain LIKE ?2')
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

        return $this->render('ACSACSPanelBundle:Domain:index.html.twig', array(
            'entities' => $entities,
            'term' => $term,
            'search_action' => 'domain_search',
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

        $dnsdomains = $em->getRepository('ACSACSPanelBundle:DnsDomain')->findByDomain($entity);
        $maildomains = $em->getRepository('ACSACSPanelBundle:MailDomain')->findByDomain($entity);

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:Domain:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'dnsdomains' => $dnsdomains,
            'maildomains' => $maildomains,
        ));
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
            return $this->render('ACSACSPanelBundle:Error:resources.html.twig', array(
                'entity' => 'Domain'
            ));
        }

        $entity = new Domain();
        $form   = $this->createForm(new DomainType($this->container), $entity);

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
        $form = $this->createForm(new DomainType($this->container), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setEnabled(true);

            #$this->container->get('event_dispatcher')->dispatch(DnsEvents::DOMAIN_BEFORE_ADD, new FilterDnsEvent($entity,$em));

            $em->persist($entity);

            $em->flush();

            if($form['add_dns_domain']->getData()){
                $dnsdomain = new DnsDomain();
                $dnsdomain->setDomain($entity);
                $dnsdomain->setType('master');
                $dnsdomain->setEnabled(true);
                $dnstypes = $em->getRepository('ACSACSPanelBundle:ServiceType')->getDNSServiceTypes();
                // TODO: Change somehow to get a default DNS server
                $dnsservice = $em->getRepository('ACSACSPanelBundle:Service')->findByType($dnstypes);

                $dnsdomain->setService($dnsservice[0]);

                $em->persist($dnsdomain);
                $em->flush();
                $this->container->get('event_dispatcher')->dispatch(DnsEvents::DNS_AFTER_DOMAIN_ADD, new FilterDnsEvent($dnsdomain,$em));
            }

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

        $editForm = $this->createForm(new DomainType($this->container), $entity);
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
        $editForm = $this->createForm(new DomainType($this->container), $entity);
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
