<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\FosUser;

/**
 * Hosting controller.
 *
 */
class HostingController extends Controller
{
    public function registerHostingAction()
    {
        $fosuser = $this->get('security.context')->getToken()->getUser();

        $flow = $this->get('acs.form.flow.register_hosting');

        $flow->bind($fosuser);

        // form of the current step
        $form = $flow->createForm();

        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            if($flow->nextStep()){
                $form = $flow->createForm();
            }else{
                $em = $this->getDoctrine()->getEntityManager();
                //$domain = $flow->getFormData();
                //print_r($domain->getKeys());
                //exit;
                $em->persist($fosuser);
                $em->flush();

                return $this->render('ACSACSPanelBundle:Flows:showHosting.html.twig', array(
                    'form' => $form->createView(),
                    'flow' => $flow,
                ));

            }
        }

        return $this->render('ACSACSPanelBundle:Flows:registerHosting.html.twig', array(
            'form' => $form->createView(),
            'flow' => $flow,
        ));
    }
}
