<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\MailAliasDomain;
use ACS\ACSPanelBundle\Form\MailAliasDomainType;

/**
 * MailAliasDomain controller.
 *
 */
class MailAliasDomainController extends Controller
{
    /**
     * Lists all MailAliasDomain entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ACSACSPanelBundle:MailAliasDomain')->findAll();

        return $this->render('ACSACSPanelBundle:MailAliasDomain:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a MailAliasDomain entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:MailAliasDomain')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailAliasDomain entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:MailAliasDomain:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new MailAliasDomain entity.
     *
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$user->canUseResource('MailAliasDomain',$em)) {
            throw new \Exception('You don\'t have enough resources!');
        }

        $entity = new MailAliasDomain();
        $form   = $this->createForm(new MailAliasDomainType(), $entity);

        return $this->render('ACSACSPanelBundle:MailAliasDomain:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new MailAliasDomain entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new MailAliasDomain();
        $form = $this->createForm(new MailAliasDomainType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mailaliasdomain_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:MailAliasDomain:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing MailAliasDomain entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:MailAliasDomain')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailAliasDomain entity.');
        }

        $editForm = $this->createForm(new MailAliasDomainType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:MailAliasDomain:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing MailAliasDomain entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:MailAliasDomain')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MailAliasDomain entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new MailAliasDomainType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mailaliasdomain_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:MailAliasDomain:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a MailAliasDomain entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:MailAliasDomain')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MailAliasDomain entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('mailaliasdomain'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
