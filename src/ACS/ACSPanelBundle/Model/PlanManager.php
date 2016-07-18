<?php
/**
 * PlanManager
 *
 * @author Genar
 */
namespace ACS\ACSPanelBundle\Model;

class PlanManager
{
    private $em;

    public function setEm($em)
    {
        $this->em = $em;
    }

    public function attachToUser($plan, $user)
    {
        $em = $this->em;

        if ($user->addPlan($plan)) {
            $em->persist($user);
            $em->flush($user);
            return true;
        }

        return false;
    }
}
