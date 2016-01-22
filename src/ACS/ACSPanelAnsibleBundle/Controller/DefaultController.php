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
        $meta = $this->generateMeta();

        $inventory->setGroups($groups);
        $inventory->setMeta($meta);

        return $inventory;
    }

    private function retrieveGroups()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $this
            ->get('servicetype_repository')
            ->getWithHosts()
        ;

        $processed = array();
        $hosts = array();

        $allGroup = new Group();
        $allSettings = $this->get('acs.setting_manager')->getSystemSettings();

        foreach ($entities as $k => $entity) {
            $group = new Group();
            $services = $entity->getServices();

            foreach ($services as $service) {
                $group->addHost($service->getServer()->getHostname());
                $allGroup->addHost($service->getServer()->getHostname());
            }

            $processed[$entity->getSlug()] = $group;
        }

        $allVars = array();
        foreach ($allSettings as $setting) {
            $allVars[$setting->getSettingKey()] = $setting->getValue();
        }
        $allGroup->setVars($allVars);

        $processed['all'] = $allGroup;

        return $processed;
    }

    public function generateMeta()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $this
            ->get('server_repository')
            ->findAll()
        ;

        $processed = array();

        foreach ($entities as $server) {
            $services = $server->getServices();

            foreach ($services as $service) {
                $settings = $service->getSettings();

                foreach ($settings as $setting) {
                    $processed[$server->getHostname()][$setting->getSettingKey()] = $setting->getValue();
                }
            }
        }

        return $processed;
    }
}
