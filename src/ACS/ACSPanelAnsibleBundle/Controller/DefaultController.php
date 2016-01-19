<?php

namespace ACS\ACSPanelAnsibleBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

use ACS\ACSPanelAnsibleBundle\Model\Inventory;
use ACS\ACSPanelAnsibleBundle\Model\Group;

class DefaultController extends FOSRestController
{
    /**
     * @Rest\View(serializerGroups={"inventory"})
     */
    public function indexAction()
    {
        $inventory = new Inventory();

        $groups = $this->retrieveGroups();
        $hosts = $this->retrieveHosts();

        $inventory->setGroups($groups);
        $inventory->setHosts($hosts);

        return $inventory;
    }

    private function retrieveHosts()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $this
            ->get('server_repository')
            ->getUserViewable(
                $this
                ->get('security.context')
                ->getToken()
                ->getUser()
            )
        ;

        return $entities;
    }

    private function retrieveGroups()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $this
            ->get('servicetype_repository')
            ->getWithHosts()
        ;

        $processed = array();

        foreach ($entities as $k => $entity) {
            $group = new Group();
            $services = $entity->getServices();

            foreach ($services as $service) {
                $group->addHost($service->getServer()->getHostname());
            }

            $processed[$entity->getSlug()] = $group;
        }

        return $processed;
    }
}
