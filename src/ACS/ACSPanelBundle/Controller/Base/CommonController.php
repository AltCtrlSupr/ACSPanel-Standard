<?php

namespace ACS\ACSPanelBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Common actions controller.
 *
 * This class is pretended to group all Controller common
 * actions to be DRY
 */
class CommonController extends FOSRestController
{
    private $entityRepository;
    private $entityRouteBase;

    public function setEntityRepository($entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    public function setEntityRouteBase($entityRoute)
    {
        $this->entityRouteBase = $entityRoute;
    }

    public function getEntity($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($this->entityRepository)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException(sprintf('Unable to find %s entity.', $entityRepository));
        }

        if (!$entity->userCanSee(
            $this->get('security.token_storage'),
            $this->get('security.authorization_checker')
        )) {
            throw new \Exception('You cannot edit this entity!');
        }

        return $entity;
    }

    /**
     * Deletes entity.
     *
     * @Rest\Delete()
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $entity = $this->getEntity($id);

            $em = $this->getDoctrine()->getManager();
            $em->remove($entity);
            $em->flush();
        }

        $view = $this->routeRedirectView($this->entityRouteBase, array(), 201);
        return $this->handleView($view);
    }

    public function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

}
