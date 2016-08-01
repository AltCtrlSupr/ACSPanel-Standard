<?php

namespace ACS\ACSPanelBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\MailLogrcvd;

/**
 * MailLogrcvd controller.
 *
 */
class MailLogrcvdController extends Controller
{
    /**
     * Lists all MailLogrcvd entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $this->get('maillogrcvd_repository')->getUserViewable($this->get('security.token_storage')->getToken()->getUser());

        return $this->render('ACSACSPanelBundle:MailLogrcvd:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a MailLogrcvd entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:MailLogrcvd')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailLogrcvd entity.');
        }

        if (!$entity->userCanSee(
            $this->get('security.token_storage'),
            $this->get('security.authorization_checker')
        )) {
            throw new \Exception('You cannot edit this entity!');
        }

        return $this->render('ACSACSPanelBundle:MailLogrcvd:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

}
