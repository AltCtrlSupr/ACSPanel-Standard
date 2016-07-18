<?php

namespace ACS\ACSPanelUsersBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelUsersBundle\Entity\FosGroup;
use ACS\ACSPanelUsersBundle\Form\FosGroupType;

/**
 * FosGroup controller.
 *
 */
class FosGroupController extends Controller
{
    /**
     * Lists all FosGroup entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ACSACSPanelUsersBundle:FosGroup')->findAll();

        return $this->render('ACSACSPanelBundle:FosGroup:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a FosGroup entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelUsersBundle:FosGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FosGroup entity.');
        }

        return $this->render('ACSACSPanelBundle:FosGroup:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Displays a form to create a new FosGroup entity.
     *
     */
    public function newAction()
    {
        $entity = new FosGroup();
        $form   = $this->createForm(new FosGroupType(), $entity);

        return $this->render('ACSACSPanelBundle:FosGroup:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new FosGroup entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new FosGroup();
        $form = $this->createForm(new FosGroupType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('groups_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:FosGroup:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }
}
