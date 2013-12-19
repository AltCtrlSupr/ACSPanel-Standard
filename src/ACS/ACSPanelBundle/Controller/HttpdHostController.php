<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\DnsRecord;
use ACS\ACSPanelBundle\Entity\HttpdHost;
use ACS\ACSPanelBundle\Entity\Domain;
use ACS\ACSPanelBundle\Form\UserHttpdHostType;
use ACS\ACSPanelBundle\Modules\Domain as DomainModule;

use ACS\ACSPanelBundle\Event\FilterDnsEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

use ACS\ACSPanelBundle\Event\DnsEvents;

/**
 * HttpdHost controller.
 *
 */
class HttpdHostController extends Controller
{
    /**
     * Lists all HttpdHost entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // IF is admin can see all the hosts, if is user only their ones...
        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $entities = $em->getRepository('ACSACSPanelBundle:HttpdHost')->findAll();
        }elseif(true === $this->get('security.context')->isGranted('ROLE_RESELLER')){
            $entities = $em->getRepository('ACSACSPanelBundle:HttpdHost')->findByUsers($this->get('security.context')->getToken()->getUser()->getIdChildIds());
        }elseif(true === $this->get('security.context')->isGranted('ROLE_USER')){
            $entities = $em->getRepository('ACSACSPanelBundle:HttpdHost')->findByUser($this->get('security.context')->getToken()->getUser());
        }


        $paginator  = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/
        );

        return $this->render('ACSACSPanelBundle:HttpdHost:index.html.twig', array(
            'entities' => $entities,
            'search_action' => 'httpdhost_search',
        ));
    }

    /**
     * Finds and displays a HttpdHost entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:HttpdHost')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HttpdHost entity.');
        }

        if (!$entity->userCanSee($this->get('security.context'))) {
            throw new \Exception('You cannot edit this entity!');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:HttpdHost:show.html.twig', array(
            'search_action' => 'httpdhost_search',
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a LogItem search results.
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('GedmoLoggable:LogEntry');

        $term = $request->request->get('term');

        $query = $rep->createQueryBuilder('li')
            ->where('li.id = ?1')
            ->orWhere('li.objectId = ?1')
            ->orWhere('li.action LIKE ?1')
            ->orWhere('li.objectClass LIKE ?1')
            ->orWhere('li.data LIKE ?1')
            ->orWhere('li.username LIKE ?1')
            ->setParameter('1',$term)
            ->getQuery();

        $entities = $query->execute();

        return $this->render('ACSACSPanelBundle:LogItem:index.html.twig', array(
            'search_action' => 'httpdhost_search',
            'term' => $term,
            'entities' => $entities,
        ));

    }


    /**
     * Displays a form to create a new HttpdHost entity.
     *
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$user->canUseResource('HttpdHost',$em)) {
            throw new \Exception('You don\'t have enough resources!');
        }

        $entity = new HttpdHost();
        $entity->setEnabled(true);
        $form   = $this->createForm(new UserHttpdHostType(), $entity);

        return $this->render('ACSACSPanelBundle:HttpdHost:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new HttpdHost entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new HttpdHost();
        $form = $this->createForm(new UserHttpdHostType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setEnabled(true);
            $em->persist($entity);

            // We create A type DNS record automatically
            // Check if the domain exists in the dns_domains table
            $this->addDnsRegister($entity->getDomain()->getName());

            // If is required we create www. alias
            if($form['add_www_alias']->getData()){
                $wwwalias = new \ACS\ACSPanelBundle\Entity\Domain();
                $wwwalias->setDomain('www.'.$entity->getDomain()->getDomain());
                // TODO: check set enabled isn't working
                $wwwalias->setEnabled($entity->getEnabled());
                $wwwalias->setParentDomain($entity->getDomain());
                 $wwwalias->setIsHttpdAlias(true);
                $em->persist($wwwalias);
                // We create A type DNS record automatically
                // Check if the domain exists in the dns_domains table
                $this->addDnsRegister($wwwalias->getDomain(), true);
            }

            // Add the dns record if user requested
            if($form['add_dns_record']->getData()){
                $dnsrecord = new DnsRecord();
                $dnsdomain = $em->getRepository('ACSACSPanelBundle:DnsDomain')->findOneByDomain($entity->getDomain());
                $dnsrecord->setDnsDomain($dnsdomain);
                $dnsrecord->setName($entity->getDomain()->getDomain());
                $dnsrecord->setType('A');
                if($entity->getProxyService())
                    $dnsrecord->setContent($entity->getProxyService()->getIp()->getIp());
                else
                    $dnsrecord->setContent($entity->getService()->getIp()->getIp());
                $em->persist($dnsrecord);

                $this->container->get('event_dispatcher')->dispatch(DnsEvents::DNS_AFTER_RECORD_ADD, new FilterDnsEvent($dnsrecord, $em));
            }


            $em->flush();

            return $this->redirect($this->generateUrl('httpdhost_show', array('id' => $entity->getId())));
        }


        return $this->render('ACSACSPanelBundle:HttpdHost:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Adds the dns register if it's don't exists
     */
    public function addDnsRegister($domain_name, $is_www = false)
    {
        $em = $this->getDoctrine()->getManager();

        $names_check = array($domain_name,$this->getDomain($domain_name));

        $domain_exists = $this->dnsAliasOrDomainExists($names_check);

        if($domain_exists){
            foreach($domain_exists as $domain){
                $records = $this->dnsRecordExists($names_check);
                if($records){
                    foreach($records as $record){
                        if($record->getType() == 'A')
                            $this->get('session')->getFlashBag()->add('notice',$this->get('translator')->trans('The A record for %record% domain already exists', array('%record%' => $record->getName())));
                        elseif($record->getType() == 'CNAME')
                            $this->get('session')->getFlashBag()->add('notice',$this->get('translator')->trans('The CNAME record for %record% domain already exists', array('%record%' => $record->getName())));
                    }
                }else{
                    $dnsrecord = new \ACS\ACSPanelBundle\Entity\DnsRecord();
                    $dnsrecord->setDnsDomain($domain);
                    $dnsrecord->setName($domain_name);
                    if($is_www){
                        $content = $domain->getName();
                        $dnsrecord->setType('CNAME');
                        $this->get('session')->getFlashBag()->add('notice',$this->get('translator')->trans('A dns record type CNAME has been created for the host %domain_name%', array('%domain_name%' => $domain_name)));
                    }else{
                        $content = $domain->getService()->getIp()->__toString();
                        $dnsrecord->setType('A');
                        $this->get('session')->getFlashBag()->add('notice',$this->get('translator')->trans('A dns record type A has been created for the host %domain_name%', array('%domain_name%' => $domain_name)));
                    }
                    $dnsrecord->setContent($content);
                    $em->persist($dnsrecord);
                }
            }
        }else{
            $this->get('session')->getFlashBag()->add('notice',$this->get('translator')->trans('The host %domain_name% has not DNS domain', array('%domain_name%' => $domain_name)));
        }
    }


    /**
     * Displays a form to edit an existing HttpdHost entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:HttpdHost')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HttpdHost entity.');
        }

        $editForm = $this->createForm(new UserHttpdHostType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:HttpdHost:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing HttpdHost entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:HttpdHost')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HttpdHost entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new UserHttpdHostType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('httpdhost_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:HttpdHost:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a HttpdHost entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:HttpdHost')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find HttpdHost entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('httpdhost'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
            ;
    }

    /**
     * Check if the host has dns_domain
     * @todo: Check for a better place to put this function
     */
    public function dnsAliasOrDomainExists($names = array())
    {
        //TODO: To fix
        return false;
        $em = $this->getDoctrine()->getManager();
        // check if thereis a full or alias dns domain
        //$domain = new Domain();
        //$domain->setDomain($names);
        $dnsdomains = $em->getRepository('ACSACSPanelBundle:DnsDomain')->findByDomain($domain);
        if(count($dnsdomains))
            return $dnsdomains;

        //return false;
    }

    /**
     * Check if the dns register exists for the specified type of register
     */
    public function dnsRecordExists($names = array())
    {
        $em = $this->getDoctrine()->getManager();
        // check if thereis a full or alias dns domain
        $dnsdomains = $em->getRepository('ACSACSPanelBundle:DnsRecord')->findByName($names);
        if(count($dnsdomains))
            return $dnsdomains;

        return false;
    }


    /*
     * Extracts only the domain.tls from a url
     * @todo: Check for a better place to put his
     */
    function getDomain($url) {
        $domain_tools = new DomainModule($url);
        return $domain_tools->get_reg_domain();
    }

    public function setenabledAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ACSACSPanelBundle:HttpdHost')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Httpd Host entity.');
        }

        $entity->setEnabled(!$entity->getEnabled());
        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('httpdhost'));
    }

}
