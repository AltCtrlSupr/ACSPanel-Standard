<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\FosUser;
use ACS\ACSPanelBundle\Entity\UserPlan;
use ACS\ACSPanelBundle\Form\FosUserType;

use ACS\ACSPanelBundle\Event\FilterUserEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

use ACS\ACSPanelBundle\Event\UserEvents;


/**
 * FosUser controller.
 *
 */
class FosUserController extends Controller

{
    /**
     * Lists all FosUser entities.
     *
     */
    public function indexAllAction()
    {
        $em = $this->getDoctrine()->getManager();

        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $entities = $em->getRepository('ACSACSPanelBundle:FosUser')->findAll();
        }else{
            $user = $this->get('security.context')->getToken()->getUser();
            //$childs = $user->getIdChildIds();

            //$entities = $em->getRepository('ACSACSPanelBundle:FosUser')->findBy(array('id' => $childs));
            $entities = $em->getRepository('ACSACSPanelBundle:FosUser')->findByParentUser($user->getId());
        }

        //$dispatcher = new EventDispatcher();
        $this->container->get('event_dispatcher')->dispatch(UserEvents::USER_REGISTER, new FilterUserEvent($entities));

        return $this->render('ACSACSPanelBundle:FosUser:index.html.twig', array(
            'search_action' => 'user_search',
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a FosUser search results.
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('ACSACSPanelBundle:FosUser');

        $term = $request->request->get('term');

        $query = $rep->createQueryBuilder('u')
            ->where('u.id = ?1')
            ->orWhere('u.username LIKE ?1')
            ->orWhere('u.email LIKE ?1')
            ->orWhere('u.roles LIKE ?1')
            ->orWhere('u.firstname LIKE ?1')
            ->orWhere('u.lastname LIKE ?1')
            ->orWhere('u.uid = ?1')
            ->orWhere('u.gid = ?1')
            ->setParameter('1',$term)
            ->getQuery();

        $entities = $query->execute();

        return $this->render('ACSACSPanelBundle:FosUser:index.html.twig', array(
            'search_action' => 'user_search',
            'term' => $term,
            'entities' => $entities,
        ));

    }

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $entities = $em->getRepository('ACSACSPanelBundle:FosUser')->findAll();
        }elseif(true === $this->get('security.context')->isGranted('ROLE_ADMIN')){
            $entities = $em->getRepository('ACSACSPanelBundle:FosUser')->findBy(array('parent_user' => $this->get('security.context')->getToken()->getUser()->getIdChildIds()));
        }else{
            $user = $this->get('security.context')->getToken()->getUser();
            $entities = $em->getRepository('ACSACSPanelBundle:FosUser')->findBy(array('parent_user' => $user->getId()));
        }

        $paginator  = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/
        );


        return $this->render('ACSACSPanelBundle:FosUser:index.html.twig', array(
            'search_action' => 'user_search',
            'entities' => $entities,
        ));
    }

    public function indexAdminAction()
    {
        $em = $this->getDoctrine()->getManager();

        $groups = $em->getRepository('ACSACSPanelBundle:FosGroup')->findByName(array('admin'));

		$entities = array();

		foreach($groups as $group ){
			$entities = $group->getUsers();
		}

        return $this->render('ACSACSPanelBundle:FosUser:index.html.twig', array(
            'search_action' => 'user_search',
            'entities' => $entities,
        ));
    }

    public function indexResellerAction()
    {
        $em = $this->getDoctrine()->getManager();

        $groups = $em->getRepository('ACSACSPanelBundle:FosGroup')->findByName(array('reseller'));

		$entities = array();

		foreach($groups as $group ){
			$entities = $group->getUsers();
		}

        return $this->render('ACSACSPanelBundle:FosUser:index.html.twig', array(
            'search_action' => 'user_search',
            'entities' => $entities,
        ));
     }

    /**
     * Finds and displays a FosUser entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:FosUser')->find($id);


        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:FosUser:show.html.twig', array(
            'search_action' => 'user_search',
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new FosUser entity.
     *
     */
    public function newAction()
    {

        $entity = new FosUser();

        $form = $this->createForm(new FosUserType(), $entity, array(
            'em' => $this->getDoctrine()->getEntityManager(),
        ));

        return $this->render('ACSACSPanelBundle:FosUser:new.html.twig', array(
            'search_action' => 'user_search',
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new FosUser entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new FosUser();
        $form = $this->createForm(new FosUserType(), $entity, array(
            'em' => $this->getDoctrine()->getEntityManager(),
        ));
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            // Persisting plans
            // @todo: Do this with events
            /*$postData = $request->request->get('acs_acspanelbundle_fosusertype');
            if(isset($postData['puser'])){
                $plans = $postData['puser'];

                foreach ($plans as $plan) {
                    $new_plan = new UserPlan();
                    $new_plan->setPuser($entity);
                    $assignplan = $em->getRepository('ACSACSPanelBundle:Plan')->find($plan['uplans']);
                    $new_plan->setUplans($assignplan);
                    $em->persist($new_plan);
                }

            }*/

            // Password encode setting
            $userManager = $this->container->get('fos_user.user_manager');
            $entity->setPlainPassword($entity->getPassword());
            $userManager->updatePassword($entity);
            $userManager->updateUser($entity);

            $em->persist($entity);
            $em->flush();

            $dispatcher = new EventDispatcher();

            $dispatcher->dispatch(UserEvents::USER_REGISTER, new FilterUserEvent($entity));

            return $this->redirect($this->generateUrl('users_edit', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelBundle:FosUser:new.html.twig', array(
            'search_action' => 'user_search',
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing FosUser entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:FosUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FosUser entity.');
        }

        $editForm = $this->createForm(new FosUserType(), $entity, array(
            'em' => $this->getDoctrine()->getEntityManager(),
        ));

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelBundle:FosUser:edit.html.twig', array(
            'search_action' => 'user_search',
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing FosUser entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelBundle:FosUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FosUser entity.');
        }


        $originalPlans = array();

        // Create an array of the current Tag objects in the database
        foreach ($entity->getPuser() as $plan) {
            $originalPlans[] = $plan;
        }


        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FosUserType(), $entity, array(
            'em' => $this->getDoctrine()->getEntityManager(),
        ));

        $editForm->bind($request);

        if ($editForm->isValid()) {
            // filter $originalPlans to contain tags no longer present
            foreach ($entity->getPuser() as $plan) {
                foreach ($originalPlans as $key => $toDel) {
                    if ($toDel->getId() === $plan->getId()) {
                        unset($originalPlans[$key]);
                    }
                }
            }

            // remove the relationship between the tag and the Task
            foreach ($originalPlans as $plan) {
                // if it were a ManyToOne relationship, remove the relationship like this
                $plan->setPuser(null);

                // if you wanted to delete the Tag entirely, you can also do that
                $em->remove($plan);
            }

            $em->persist($entity);
            $em->flush();


            return $this->redirect($this->generateUrl('users_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelBundle:FosUser:edit.html.twig', array(
            'search_action' => 'user_search',
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a FosUser entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelBundle:FosUser')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FosUser entity.');
            }

            $userplans = $em->getRepository('ACSACSPanelBundle:UserPlan')->findByPuser($entity);
            foreach ($userplans as $uplan) {
                 $em->remove($uplan);
                 $em->flush();
            }


            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('users'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
