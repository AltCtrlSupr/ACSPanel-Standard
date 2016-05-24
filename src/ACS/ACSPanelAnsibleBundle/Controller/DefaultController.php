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
        $meta['hostvars'] = $this->generateMeta();

        $inventory->setGroups($groups);
        $inventory->setMeta($meta);

        return $inventory;
    }

    /**
     * retrieveGroups
     *
     * @access private
     * @return void
     */
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

    /**
     * generateMeta
     *
     * @access public
     * @return void
     */
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

                // Showing httpdhosts values
                foreach($service->getHttpdHosts() as $httpdhost) {
                    $processed[$server->getHostname()]['httpd'][$httpdhost->getDomain()->getDomain()] = array(
                        'id' => $httpdhost->getId(),
                        'name' => $httpdhost->getName(),
                        'enabled' => $httpdhost->getEnabled(),
                        'webdav' => $httpdhost->getWebdav(),
                        'ftp' => $httpdhost->getFtp(),
                        'cgi' => $httpdhost->getCgi(),
                        'ssi' => $httpdhost->getSsi(),
                        'php' => $httpdhost->getPhp(),
                        'configuration' => $httpdhost->getConfiguration(),
                        'createdAt' => $httpdhost->getCreatedAt(),
                        'updatedAt' => $httpdhost->getUpdatedAt(),
                        'ssl' => $httpdhost->getSsl(),
                        'certificate' => $httpdhost->getCertificate(),
                        'certificateKey' => $httpdhost->getCertificateKey(),
                        'certificateChain' => $httpdhost->getCertificateChain(),
                        'certificateAuthority' => $httpdhost->getCertificateAuthority(),
                    );

                    foreach ($httpdhost->getAliases() as $k => $alias) {
                        $processed[$server->getHostname()]['httpd'][$httpdhost->getDomain()->getDomain()]['aliases'][$k] = $alias->getDomain();
                    }
                }

                // Showing dnsdomains values
                foreach ($service->getDnsDomains() as $dnsdomain) {
                    $processed[$server->getHostname()]['dns'][$dnsdomain->getDomain()->getDomain()] = array(
                        'id' => $dnsdomain->getId(),
                        'name' => $dnsdomain->getName(),
                        'domain' => $dnsdomain->getDomain()->getDomain(),
                        'master' => $dnsdomain->getMaster(),
                        'lastCheck' => $dnsdomain->getLastCheck(),
                        'type' => $dnsdomain->getType(),
                        'notifiedSerial' => $dnsdomain->getNotifiedSerial(),
                        'account' => $dnsdomain->getAccount(),
                        'enabled' => $dnsdomain->getEnabled(),
                        'createdAt' => $dnsdomain->getCreatedAt(),
                        'updatedAt' => $dnsdomain->getUpdatedAt(),
                        'public' => $dnsdomain->getPublic(),
                    );

                    foreach ($dnsdomain->getDnsRecords() as $k => $dnsrecord) {
                        $processed[$server->getHostname()]['dns'][$dnsdomain->getDomain()->getDomain()]['dnsRecords'][$k] = array(
                            'id' => $dnsrecord->getId(),
                            'name' => $dnsrecord->getName(),
                            'type' => $dnsrecord->getType(),
                            'content' => $dnsrecord->getContent(),
                            'ttl' => $dnsrecord->getTtl(),
                            'prio' => $dnsrecord->getPrio(),
                            'createdAt' => $dnsrecord->getCreatedAt(),
                            'updatedAt' => $dnsrecord->getUpdatedAt(),
                        );
                    }
                }
            }
        }

        return $processed;
    }
}
