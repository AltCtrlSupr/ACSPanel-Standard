<?php


namespace ACS\ACSPanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ACS\ACSPanelBundle\Entity\Domain;
use ACS\ACSPanelBundle\Entity\HttpdHost;
use ACS\ACSPanelBundle\Entity\DnsDomain;
use ACS\ACSPanelBundle\Entity\DnsRecord;

/**
 * Hosting controller.
 *
 */
class HostingController extends Controller
{
    public function registerHostingAction()
    {
        $fosuser = $this->get('security.token_storage')->getToken()->getUser();

        $flow = $this->get('acs.form.flow.register_hosting');

        $flow->bind($fosuser);

        // form of the current step
        $form = $flow->createForm();

        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            if($flow->nextStep()){
                $form = $flow->createForm();
            }else{
                $em = $this->getDoctrine()->getManager();
                $data = $flow->getFormData();

                // Domain Creation
                $domain = new Domain();
                $domain->setDomain($data[1]['domains']['domain']);
                $domain->setUser($fosuser);
                $domain->setEnabled(true);
                $em->persist($domain);

                // Httpd host Creation
                $httpdhost = new HttpdHost();
                $httpdhost->setDomain($domain);
                $httpdhost->setEnabled(true);
                $service = $em->getRepository('ACSACSPanelBundle:Service')->find($data[1]['service']);
                $httpdhost->setService($service);
                if($data[1]['php_hosting'] == '1')
                    $httpdhost->setPhp(true);
                $em->persist($httpdhost);

                // Dns records Creation
                if($data[2]['add_a_records'] == '1'){
                    $dnsdomain = new DnsDomain();
                    $dnsdomain->setDomain($domain);
                    $dnsdomain->setEnabled(true);
                    $dnsdomain->setType('master');
                    $dnsservice = $em->getRepository('ACSACSPanelBundle:Service')->find($data[2]['service']);
                    $dnsdomain->setService($dnsservice);
                    $em->persist($dnsdomain);

                    // A type
                    $adnsrecord = new DnsRecord();
                    $adnsrecord->setType('A');
                    $adnsrecord->setName($domain->getDomain());
                    $adnsrecord->setDnsDomain($dnsdomain);
                    $adnsrecord->setContent($service->getServer()->getIp()->getIp());
                    $em->persist($adnsrecord);

                    // NS1 type
                    $ns1dnsrecord = new DnsRecord();
                    $ns1dnsrecord->setType('NS');
                    $ns1dnsrecord->setName($domain->getDomain());
                    $ns1dnsrecord->setDnsDomain($dnsdomain);
                    // TODO: Get default DNS1 server from settings
                    //$ns1dnsrecord->setContent($service->getServer()->getIpAddress());
                    $em->persist($ns1dnsrecord);
                }

                $em->flush();

                return $this->redirect($this->generateUrl('domain_show', array('id' => $domain->getId())));
            }
        }

        return $this->render('ACSACSPanelBundle:Flows:registerHosting.html.twig', array(
            'form' => $form->createView(),
            'flow' => $flow,
        ));
    }
}
