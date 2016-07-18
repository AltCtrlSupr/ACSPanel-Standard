<?php

namespace ACS\ACSPanelWordpressBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelWordpressBundle\Entity\WPSetup;
use ACS\ACSPanelWordpressBundle\Form\WPSetupType;

use ACS\ACSPanelBundle\Entity\HttpdHost;
use ACS\ACSPanelBundle\Entity\DB;
use ACS\ACSPanelBundle\Entity\DatabaseUser;

/**
 * WPSetup controller.
 *
 */
class WPSetupController extends Controller
{
    /**
     * Lists all WPSetup entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        //
        // IF is admin can see all the hosts, if is user only their ones...
        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $entities = $em->getRepository('ACSACSPanelWordpressBundle:WPSetup')->findAll();
        }elseif(true === $this->get('security.context')->isGranted('ROLE_RESELLER')){
            $entities = $em->getRepository('ACSACSPanelWordpressBundle:WPSetup')->findByUsers($this->get('security.context')->getToken()->getUser()->getIdChildIds());
        }elseif(true === $this->get('security.context')->isGranted('ROLE_USER')){
            $entities = $em->getRepository('ACSACSPanelWordpressBundle:WPSetup')->findByUser($this->get('security.context')->getToken()->getUser());
        }

        $paginator  = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/
        );

        return $this->render('ACSACSPanelWordpressBundle:WPSetup:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a WPSetup entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelWordpressBundle:WPSetup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WPSetup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelWordpressBundle:WPSetup:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new WPSetup entity.
     *
     */
    public function newAction()
    {
        $entity = new WPSetup();
        $form   = $this->createForm(new WPSetupType($this->container), $entity);

        return $this->render('ACSACSPanelWordpressBundle:WPSetup:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new WPSetup entity.
     *
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity  = new WPSetup();

        $form = $this->createForm(new WPSetupType($this->container), $entity);
        $form->bind($request);

        if ($form->isValid()) {

            $user = $this->get('security.context')->getToken()->getUser();

            $domain = $form['domain']->getData();
            if(isset($form['user']))
                $domain->setUser($form['user']->getData());

            $em->persist($domain);

            $httpdhost = new HttpdHost();
            $httpdhost->setDomain($domain);

            // Add open_basedir wordpress directory
            $configuration = "
                <Directory '/home/$user/web/$domain/httpdocs'>
                    php_admin_value open_basedir '/home/$user/web/$domain:/srv/httpd/error:/tmp:/var/www/source'
                </Directory>";
            $httpdhost->setConfiguration($configuration);
            $em->persist($httpdhost);

            $validator = $this->get('validator');
            // Add database and user
            $wpdb = new DB();
            $wpdb->setName($user->getUsername().'_wp_'.$httpdhost->getId());
            $em->persist($wpdb);

            $dbuser = new DatabaseUser();
            $dbuser->setUsername($user->getId().'_wp_'.$httpdhost->getId());
            $dbuser->setDb($wpdb);
            $dbuser->setPassword('');
            $em->persist($dbuser);

            $entity->setHttpdHost($httpdhost);
            $entity->setDatabaseUser($dbuser);
            $entity->setEnabled(true);
            $em->persist($entity);

            $em->flush();

            return $this->redirect($this->generateUrl('wpsetup_show', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelWordpressBundle:WPSetup:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing WPSetup entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelWordpressBundle:WPSetup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WPSetup entity.');
        }

        $editForm = $this->createForm(new WPSetupType($this->container), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelWordpressBundle:WPSetup:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing WPSetup entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelWordpressBundle:WPSetup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WPSetup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new WPSetupType($this->container), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('wpsetup_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelWordpressBundle:WPSetup:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a WPSetup entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelWordpressBundle:WPSetup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find WPSetup entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('wpsetup'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
