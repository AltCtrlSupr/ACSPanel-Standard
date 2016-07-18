<?php

namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use ACS\ACSPanelBundle\Controller\Base\CommonController;

use ACS\ACSPanelBundle\Entity\Domain;
use ACS\ACSPanelBundle\Entity\DnsDomain;
use ACS\ACSPanelBundle\Form\DomainType;
use ACS\ACSPanelBundle\Event\DnsEvents;
use ACS\ACSPanelBundle\Event\FilterDnsEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Domain controller.
 *
 * @Rest\RouteResource("Domain")
 */
class DomainController extends CommonController
{
    public function __construct()
    {
        $this->setEntityRepository('ACSACSPanelBundle:Domain');
        $this->setEntityRouteBase('domain');
    }

    /**
     * Lists all Domain entities.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns all the domains owned by current user",
     * )
     *
     * @Rest\View(templateVar="entities")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // IF is admin can see all the hosts, if is user only their ones...
        $entities = $this->get('domain_repository')->getUserViewable($this->get('security.context')->getToken()->getUser());

        $view = $this->view($entities, 200)
            ->setTemplateData(array('search_action' => 'domain_search'))
        ;

        return $view;
    }

    /**
     * Finds and displays a Domain search results.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns the domains owned by current user filtered by search term",
     *  requirements={
     *      {
     *          "name"="term",
     *          "dataType"="string",
     *          "requirement"="\w+",
     *          "description"="The search term to filter"
     *      }
     *  },
     * )
     *
     * @Rest\View(template="ACSACSPanelBundle:Domain:index.html.twig", templateVar="entities")
     * @Rest\Get("/domains/{term}/search")
     */
    public function searchAction($term)
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('ACSACSPanelBundle:Domain');

        $query = $rep->createQueryBuilder('d')
            ->where('d.id = ?1')
            ->orWhere('d.domain LIKE ?2')
            ->setParameter('1', $term)
            ->setParameter('2', '%'.$term.'%')
            ->getQuery()
        ;

        $template_vars = array(
            'search_action' => 'domain_search',
            'term' => $term,
        );

        $entities = $query->execute();

        $view = $this->view($entities, 200)
            ->setTemplateData($template_vars)
        ;

        return $view;
    }

    /**
     * Finds and displays a Domain entity.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Return the domain for the passed id",
     * )
     *
     * @Rest\Get("/domains/{id}/show")
     * @Rest\View(templateVar="entity")
     */
    public function showAction($id)
    {
        $entity = $this->getEntity($id);

        $em = $this->getDoctrine()->getManager();
        $dnsdomains = $em->getRepository('ACSACSPanelBundle:DnsDomain')->findByDomain($entity);
        $maildomains = $em->getRepository('ACSACSPanelBundle:MailDomain')->findByDomain($entity);
        $delete_form = $this->createDeleteForm($id);

        $template_data = array(
            'dnsdomains' => $dnsdomains,
            'maildomains' => $maildomains,
            'delete_form' => $delete_form->createView()
        );

        $view = $this->view($entity, 200)
            ->setTemplateData($template_data)
        ;

        return $view;
    }

    /**
     * Displays a form to create a new Domain entity.
     *
     * @Rest\View(templateVar="entity")
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        if (!$user->canUseResource('Domain', $em)) {
            return $this->render('ACSACSPanelBundle:Error:resources.html.twig', array(
                'entity' => 'Domain'
            ));
        }

        $entity = new Domain();
        $form   = $this->createForm(new DomainType($this->container), $entity);

        $view = $this->view($entity, 200)
            ->setTemplateData(array('form' => $form->createView()))
        ;

        return $view;
    }

    /**
     * Creates a new Domain entity.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Creates new domain",
     * )
     *
     * @Rest\Post("/domains/create")
     * @Rest\View(template="ACSACSPanelBundle:Domain:new.html.twig", templateVar="entity")
     */
    public function createAction(Request $request)
    {
        $entity  = new Domain();
        $form = $this->createForm(new DomainType($this->container), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setEnabled(true);

            $this->container->get('event_dispatcher')->dispatch(DnsEvents::DOMAIN_BEFORE_ADD, new FilterDnsEvent($entity, $em));

            $em->persist($entity);

            $em->flush();

            if($form['add_dns_domain']->getData()){
                $this->__handleDnsCreation($entity);
            }

            // It only works with 201 code
            $view = $this->routeRedirectView('domain_show', array('id' => $entity->getId()), 201);
            return $this->handleView($view);
        }

        $view = $this->view($entity, 200)
            ->setTemplateData(array('form' => $form->createView()))
        ;

        return $view;
    }

    private function __handleDnsCreation($entity)
    {
        $dnsdomain = new DnsDomain();
        $dnsdomain->setDomain($entity);
        $dnsdomain->setType('master');
        $dnsdomain->setEnabled(true);

        $em = $this->getDoctrine()->getManager();
        $dnstypes = $em->getRepository('ACSACSPanelBundle:ServiceType')->getDNSServiceTypesIds();
        // TODO: Change somehow to get a default DNS server
        $dnsservice = $em->getRepository('ACSACSPanelBundle:Service')->findByType($dnstypes);

        if (count($dnsservice)) {
            $dnsdomain->setService($dnsservice[0]);
        }

        $this->container->get('event_dispatcher')->dispatch(DnsEvents::DNS_AFTER_DOMAIN_ADD, new FilterDnsEvent($dnsdomain, $em));

        $em->persist($dnsdomain);
        $em->flush();
    }

    /**
     * Displays a form to edit an existing Domain entity.
     *
     * @Rest\View()
     */
    public function editAction($id)
    {
        $entity = $this->getEntity($id);

        $editForm = $this->createForm(new DomainType($this->container), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Domain entity.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Edits domain identified by id",
     * )
     *
     * @Rest\View()
     */
    public function updateAction(Request $request, $id)
    {
        $entity = $this->getEntity($id);

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DomainType($this->container), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('domain_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    public function setaliasAction(Request $request, $id, $type)
    {
        $entity = $this->getEntity($id);

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
        $entity = $this->getEntity($id);

        $entity->setEnabled(!$entity->getEnabled());
        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('domain'));
    }


}
