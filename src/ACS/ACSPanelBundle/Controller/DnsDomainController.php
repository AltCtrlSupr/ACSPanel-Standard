<?php

namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ACS\ACSPanelBundle\Controller\Base\CommonController;
use FOS\RestBundle\Controller\Annotations as Rest;

use ACS\ACSPanelBundle\Entity\DnsDomain;
use ACS\ACSPanelBundle\Entity\DnsRecord;
use ACS\ACSPanelBundle\Form\DnsDomainType;

use ACS\ACSPanelBundle\Event\FilterDnsEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

use ACS\ACSPanelBundle\Event\DnsEvents;

/**
 * DnsDomain controller.
 *
 * @Rest\RouteResource("DnsDomain")
 */
class DnsDomainController extends CommonController
{
    public function __construct()
    {
        $this->setEntityRepository('ACSACSPanelBundle:DnsDomain');
        $this->setEntityRouteBase('dnsdomain');
    }

    /**
     * Lists all DnsDomain entities.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Show all DNS Domains owned by current user",
     * )
     *
     * @Rest\View(
     * "ACSACSPanelBundle:DnsDomain:index.html.twig",
     * templateVar="entities")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // IF is admin can see all the dnsdomains, if is user only their ones...
        $entities = $this->get('dnsdomain_repository')->getUserViewable($this->get('security.token_storage')->getToken()->getUser());

        return $this->view($entities, 200)
            ->setTemplateData(array('search_action' => 'dnsdomain_search'))
        ;
    }

    /**
     * Finds and displays a DnsDomain entity.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Show DNS Domain identifyed by id",
     * )
     *
     * @Rest\Get("/dnsdomains/{id}/show")
     * @Rest\View("ACSACSPanelBundle:DnsDomain:show.html.twig", templateVar="entity")
     */
    public function showAction($id)
    {
        $entity = $this->getEntity($id);

        $deleteForm = $this->createDeleteForm($id);

        $template_data = array(
            'search_action' => 'dnsdomain_search',
            'delete_form' => $deleteForm->createView()
        );

        $view = $this->view($entity, 200)
            ->setTemplateData($template_data)
        ;

        return $view;
    }

    /**
     * Displays a form to create a new DnsDomain entity.
     *
     * @Rest\View(templateVar="entity")
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if (!$user->canUseResource('DnsDomain',$em)) {
            return $this->render('ACSACSPanelBundle:Error:resources.html.twig', array(
                'entity' => 'Dns Domain'
            ));
        }

        $entity = new DnsDomain();
        $form   = $this->createForm(new DnsDomainType($this->container), $entity);

        $template_data = array(
            'search_action' => 'dnsdomain_search',
            'form'   => $form->createView(),
        );

        $view = $this->view($entity, 200)
            ->setTemplateData($template_data)
        ;

        return $view;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Creates new DNS Domain",
     * )
     *
     * @Rest\Post("/dnsdomains/create")
     * @Rest\View("ACSACSPanelBundle:DnsDomain:new.html.twig", templateVar="entity")
     */
    public function createAction(Request $request)
    {
        $entity  = new DnsDomain();
        $form = $this->createForm(DnsDomainType::class, $entity, [
            'token_storage' => $this->get('security.token_storage'),
            'domain_repository' => $this->get('domain_repository'),
            'service_repository' => $this->get('service_repository')
        ]);
        $form->submit($request->request->get('dnsdomaintype'), false);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setEnabled(true);
            $em->persist($entity);
            $em->flush();

            $this->container->get('event_dispatcher')->dispatch(DnsEvents::DNS_AFTER_DOMAIN_ADD, new FilterDnsEvent($entity,$em));

            $view = $this->routeRedirectView('dnsdomain_show', array('id' => $entity->getId()), 201);
            return $this->handleView($view);
        }

        return $form;
    }

    /**
     * Displays a form to edit an existing DnsDomain entity.
     *
     */
    public function editAction($id)
    {
        $entity = $this->getEntity($id);

        $editForm = $this->createForm(new DnsDomainType($this->container), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:DnsDomain:edit.html.twig', array(
            'entity'      => $entity,
            'search_action' => 'dnsdomain_search',
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing DnsDomain entity.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Updates DNS Domain identifyed by id",
     * )
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->getEntity($id);

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DnsDomainType($this->container), $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->container->get('event_dispatcher')->dispatch(DnsEvents::DNS_AFTER_DOMAIN_UPDATE, new FilterDnsEvent($entity,$em));

            $view = $this->routeRedirectView('dnsdomain_edit', array('id' => $id), 201);
            return $this->handleView($view);
        }

        return $this->render('ACSACSPanelBundle:DnsDomain:edit.html.twig', array(
            'search_action' => 'dnsdomain_search',
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function setenabledAction(Request $request, $id)
    {
        $entity = $this->getEntity($id);
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

    /**
     * Finds and displays a DnsDomain search results.
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('ACSACSPanelBundle:DnsDomain');

        $term = $request->request->get('term');

        $query = $rep->createQueryBuilder('d')
            ->where('d.id = ?1')
            ->innerJoin('d.domain','dom')
            ->orWhere('dom.domain LIKE ?2')
            ->orWhere('d.type LIKE ?2')
            ->orWhere('d.account LIKE ?2')
            ->setParameter('1',$term)
            ->setParameter('2','%'.$term.'%')
            ->getQuery()
        ;

        $entities = $query->execute();

        return $this->render('ACSACSPanelBundle:DnsDomain:index.html.twig', array(
            'entities' => $entities,
            'term' => $term,
            'search_action' => 'dnsdomain_search',
        ));

    }

}
