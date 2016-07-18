<?php

namespace ACS\ACSPanelUsersBundle\Controller;

use ACS\ACSPanelBundle\Controller\Base\CommonController;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use ACS\ACSPanelBundle\Entity\UserPlan;
use ACS\ACSPanelUsersBundle\Form\UserType;
use ACS\ACSPanelUsersBundle\Entity\User;
use ACS\ACSPanelUsersBundle\Event\UserEvents;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Domain controller.
 *
 * @Rest\RouteResource("User")
 */
class UserController extends CommonController
{
    public function __construct()
    {
        $this->setEntityRepository('ACSACSPanelUsersBundle:User');
        $this->setEntityRouteBase('user');
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Return all the users visible to current user",
     * )
     *
     * @Route("/", name="users")
     * @Rest\View(templateVar="entities")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $entities = $em->getRepository('ACSACSPanelUsersBundle:User')->findAll();
        }elseif(true === $this->get('security.context')->isGranted('ROLE_ADMIN')){
            $entities = $em->getRepository('ACSACSPanelUsersBundle:User')->findBy(array('parent_user' => $this->get('security.context')->getToken()->getUser()->getIdChildIds()));
        }else{
            $user = $this->get('security.context')->getToken()->getUser();
            $entities = $em->getRepository('ACSACSPanelUsersBundle:User')->findBy(array('parent_user' => $user->getId()));
        }

        $view = $this->view($entities, 200)
            ->setTemplateData(array('search_action' => 'user_search'))
        ;

        return $view;
    }

    /**
     * @Route("/search", name="user_search")
     * @Template()
     */
    public function searchAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('ACSACSPanelUsersBundle:User');

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
            ->getQuery()
        ;

        $entities = $query->execute();

        return $this->render('ACSACSPanelUsersBundle:User:index.html.twig', array(
            'search_action' => 'user_search',
            'term' => $term,
            'entities' => $entities,
        ));

    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new/", name="users_new")
     */
    public function newAction()
    {
        $entity = new User();

        $form = $this->createForm(new UserType($this->get('security.context')), $entity, array(
            'em' => $this->getDoctrine()->getManager(),
        ));

        return $this->render('ACSACSPanelUsersBundle:User:new.html.twig', array(
            'search_action' => 'user_search',
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates new user
     *
     * @Route("/create/", name="users_create")
     */
    public function createAction(Request $request)
    {
        $entity  = new User();
        $form = $this->createForm(new UserType($this->get('security.context')), $entity, array(
            'em' => $this->getDoctrine()->getManager(),
        ));
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            // Persisting plans
            $postData = $request->request->get('acs_acspanelbundle_fosusertype');
            if(isset($postData['puser'])){
                $plans = $postData['puser'];

                foreach ($plans as $plan) {
                    $assignplan = $em->getRepository('ACSACSPanelBundle:Plan')->find($plan['uplans']);
                    if($assignplan){
                        $new_plan = new UserPlan();
                        $new_plan->setPuser($entity);
                        $new_plan->setUplans($assignplan);
                        $em->persist($new_plan);
                    }
                }
            }

            // Password encode setting
            $userManager = $this->container->get('fos_user.user_manager');
            $entity->setPlainPassword($entity->getPassword());
            $userManager->updatePassword($entity);
            $userManager->updateUser($entity);

            $em->persist($entity);
            $em->flush();

            $this->handleServiceAssignment($form, $entity);

            return $this->redirect($this->generateUrl('users_edit', array('id' => $entity->getId())));
        }

        return $this->render('ACSACSPanelUsersBundle:User:new.html.twig', array(
            'search_action' => 'user_search',
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays show User entity.
     *
     * @Route("/show/{id}", name="users_show")
     */

    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelUsersBundle:User')->find($id);

        if (!$entity->userCanSee($this->get('security.context'))) {
            throw new \Exception('You cannot edit this entity!');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelUsersBundle:User:show.html.twig', array(
            'search_action' => 'user_search',
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));

    }

    /**
     * Switch the session to other user to admin purposes
     * @Route("/switch/{id}", name="users_switch")
     */
    public function switchAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $curr_user = $this->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('ACSACSPanelUsersBundle:User')->find($id);

        if (true === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN') || $curr_user == $user->getParentUser()) {

            $loginmanager = $this->get('fos_user.security.login_manager');
            $loginmanager->loginUser('main', $user, new Response());

            //$this->get('session')->set('is_superior_user','true');

            return $this->redirect($this->generateUrl('acs_acspanel_homepage'));
        }else{
            throw $this->createNotFoundException('You cannot do this');
        }

    }

    /**
     * Users edit
     * @Route("/edit/{id}", name="users_edit")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelUsersBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserType($this->get('security.context')), $entity, array(
            'em' => $em,
        ));

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ACSACSPanelUsersBundle:User:edit.html.twig', array(
            'search_action' => 'user_search',
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/delete/{id}", name="users_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ACSACSPanelUsersBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $userplans = $em->getRepository('ACSACSPanelBundle:UserPlan')->findByPuser($entity);
            foreach ($userplans as $uplan) {
                 $em->remove($uplan);
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('users'));
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/update/{id}", name="users_update")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ACSACSPanelUsersBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $originalPlans = array();

        // Create an array of the current Tag objects in the database
        foreach ($entity->getPuser() as $plan) {
            $originalPlans[] = $plan;
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new UserType($this->get('security.context')), $entity, array(
            'em' => $this->getDoctrine()->getManager(),
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

            $this->handleServiceAssignment($editForm, $entity);

            return $this->redirect($this->generateUrl('users_edit', array('id' => $id)));
        }

        return $this->render('ACSACSPanelUsersBundle:User:edit.html.twig', array(
            'search_action' => 'user_search',
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    private function handleServiceAssignment($form, $entity)
    {
        $allowed_services = $form->get('allowed_services')->getData();

        foreach ($allowed_services as $service) {
            $service = $service['uservices'];
            $this->get('service_manager')->attachToUser($service, $entity);
        }
    }
}
