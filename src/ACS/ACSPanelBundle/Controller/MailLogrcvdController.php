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
        $entities = $em->getRepository('ACSACSPanelBundle:MailLogrcvd')->findByUsers($this->get('security.context')->getToken()->getUser()->getIdChildIds());


        $paginator  = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/
        );

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

        return $this->render('ACSACSPanelBundle:MailLogrcvd:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

}
