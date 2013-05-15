<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\LogItem;
use ACS\ACSPanelBundle\Form\LogItemVersionsType;

/**
 * LogItem controller.
 *
 */
class LogItemController extends Controller
{
    /**
     * Lists all LogItem entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // IF is admin can see all the hosts, if is user only their ones...
        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $entities = $em->getRepository('GedmoLoggable:LogEntry')->findAll();
        }elseif(true === $this->get('security.context')->isGranted('ROLE_RESELLER')){
            $entities = $em->getRepository('GedmoLoggable:LogEntry')->findByUsername($this->get('security.context')->getToken()->getUser()->getChildUsernames());
        }elseif(true === $this->get('security.context')->isGranted('ROLE_USER')){
            $entities = $em->getRepository('GedmoLoggable:LogEntry')->findByUser($this->get('security.context')->getToken()->getUser());
        }

        return $this->render('ACSACSPanelBundle:LogItem:index.html.twig', array(
            'search_action' => 'logitem_search',
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a LogItem entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GedmoLoggable:LogEntry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LogItem entity.');
        }

        return $this->render('ACSACSPanelBundle:LogItem:show.html.twig', array(
            'entity'      => $entity,
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
            ->setParameter('1',"%".$term."%")
            ->getQuery();

        $entities = $query->execute();

        return $this->render('ACSACSPanelBundle:LogItem:index.html.twig', array(
            'search_action' => 'logitem_search',
            'term' => $term,
            'entities' => $entities,
        ));

    }

    /**
     * Finds and displays a LogItem entity versions form.
     *
     */
    public function versionsAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GedmoLoggable:LogEntry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HttpdHost entity.');
        }

        $loggedobject = $em->getRepository($entity->getObjectClass())->find($entity->getObjectId());

        if (!$loggedobject) {
            throw $this->createNotFoundException('Unable to find the associed entity.');
        }

        $editForm = $this->createForm(new LogItemVersionsType($loggedobject), $entity);

        return $this->render('ACSACSPanelBundle:LogItem:versions.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));

    }

    /**
     * Edits an existing LogItem entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GedmoLoggable:LogEntry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LogItem entity.');
        }

        $refobjrepo = $em->getRepository($entity->getObjectClass());

        $refobject = $refobjrepo->find($entity->getObjectId());

        $editForm = $this->createForm(new LogItemVersionsType($refobject), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $repo = $em->getRepository('GedmoLoggable:LogEntry');
            $values = $request->request->get('acs_acspanelbundle_logitemversionstype');

            $version = intval($values['version']);

            $repo->revert($refobject , $version);

            $em->persist($refobject);
            $em->flush();

            return $this->redirect($this->generateUrl('logs'));
        }

        return $this->render('ACSACSPanelBundle:LogItem:versions.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }


}
