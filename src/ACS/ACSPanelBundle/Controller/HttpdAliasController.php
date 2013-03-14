<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\Domain;
use ACS\ACSPanelBundle\Form\HttpdAliasType;

/**
 * Domain controller.
 *
 */
class HttpdAliasController extends Controller
{
    /**
     * Lists all Domain entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // IF is admin can see all the hosts, if is user only their ones...
        if (true === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $entities = $em->getRepository('ACSACSPanelBundle:Domain')->findAliases();
        }elseif(true === $this->get('security.context')->isGranted('ROLE_USER')){
            $entities = $em->getRepository('ACSACSPanelBundle:Domain')->findAliasesByUser($this->get('security.context')->getToken()->getUser());
        }

        return $this->render('ACSACSPanelBundle:HttpdAlias:index.html.twig', array(
            'entities' => $entities,
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

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:HttpdAlias:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Domain entity.
     *
     */
    public function newAction($httpdhost_id = '')
    {


        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        //if (!$user->canUseResource('HttpdAlias',$em)) {
            //throw new \Exception('You don\'t have enough resources!');
        //}

        $entity = new Domain();

        if($httpdhost_id){
            $em = $this->getDoctrine()->getManager();
            $entity->setParentDomain($em->getRepository('ACSACSPanelBundle:HttpdHost')->find($httpdhost_id)->getDomain());
        }

        $form   = $this->createForm(new HttpdAliasType(), $entity);

        return $this->render('ACSACSPanelBundle:HttpdAlias:new.html.twig', array(
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

        $entity->setIsHttpdAlias(true);
        $entity->setEnabled(true);

        $form = $this->createForm(new HttpdAliasType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('httpdhost_show', array('id' => $entity->getParentDomain()->getHttpdHost()->getId())));
        }

        return $this->render('ACSACSPanelBundle:HttpdAlias:new.html.twig', array(
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

        $editForm = $this->createForm(new HttpdAliasType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:HttpdAlias:edit.html.twig', array(
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
        $editForm = $this->createForm(new HttpdAliasType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('httpdalias_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:HttpdAlias:edit.html.twig', array(
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

        return $this->redirect($this->generateUrl('httpdalias'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
