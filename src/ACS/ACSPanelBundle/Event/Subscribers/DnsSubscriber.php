<?php
namespace ACS\ACSPanelBundle\Event\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ACS\ACSPanelBundle\Event\FilterDnsEvent;
use ACS\ACSPanelBundle\Entity\DnsRecord;

class DnsSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'dns.after.domain.add' => array(
                array('addSOA',100),
                array('addNS',150),
            ),
            'dns.after.record.update' => array(
                array('incrementSOA',100),
            ),
            'dns.after.record.add' => array(
                array('incrementSOA',100),
            ),
            'dns.after.record.delete' => array(
                array('incrementSOA',100),
            ),
            'domain.before.add' => array(
                array('cloneDnsRecords',100),
            )
        );
    }

    public function addSOA(FilterDnsEvent $dnsfilter)
    {
        $em = $dnsfilter->getEm();

        $entity = $dnsfilter->getDnsDomain();

        $soa = new DnsRecord();
        $soa->setDnsDomain($entity);
        $soa->setUser($entity->getUser());
        // TODO: Conf ttl times from config
        $soa->setTtl('3600');
        $soa->setType('SOA');
        $domainName = $em->getRepository('ACSACSPanelBundle:Domain')->find($entity->getDomain())->getName();
        // TODO: Conf ttl times from config
        $soa->setContent($domainName.' webmaster@'.$domainName.' 1 3600 3600 3600 3600');
        $soa->setName($domainName);
        $soa->setCreatedAtValue();

        $em->persist($soa);
        $em->flush();

    }

    public function addNS(FilterDnsEvent $dnsfilter)
    {
    }

    public function incrementSOA(FilterDnsEvent $dnsfilter)
    {
        $em = $dnsfilter->getEm();
        $entity = $dnsfilter->getDnsDomain();

        $soa=$em->getRepository('ACSACSPanelBundle:DnsRecord')->findBy(array('type'=>'SOA','dns_domain'=>$entity->getDnsDomain()));
        $soa=$soa[0];
        $soacontent=explode(' ',$soa->getContent());
        if(count($soacontent)<3){$soacontent[2]=0;}
        $soacontent[2]=$soacontent[2]+1;
        $soa->setContent(implode(' ',$soacontent));
        $em->persist($soa);
        $em->flush();
    }

    public function cloneDnsRecords(FilterDnsEvent $dnsfilter)
    {
        $em = $dnsfilter->getEm();
        $entity = $dnsfilter->getDnsDomain();

        // Getting the Dns domain for entity parent domain
        $parentDnsDomain = $em->getRepository('ACSACSPanelBundle:DnsDomain')->findOneBy(array('domain'=>$entity->getParentDomain()));

        // Getting the dnsrecord for entity parent domain
        $dnsRecords = $em->getRepository('ACSACSPanelBundle:DnsRecord')->findBy(array('dns_domain' => $parentDnsDomain));

        foreach($dnsRecords as $dnsRecord){
         $pattern = '/'.$parentDnsDomain->getDomain().'$/';

            $name = preg_replace($pattern,$entity->getName(),$dnsRecord->getName());
            $type=$dnsRecord->getType();
            $content = preg_replace($pattern,$entity->getName(),$dnsRecord->getContent());

            $exist=$em->getRepository('ACSACSPanelBundle:DnsRecord')->findBy(array('name'=>$name,'type'=>$type,'content'=>$content));
            if(!count($exist)){
                $newRecord=new DnsRecord();
                $newRecord->setName($name);
                $newRecord->setType($type);

                $newRecord->setContent($content);
                $newRecord->setTtl($dnsRecord->getTtl());
                $newRecord->setPrio($dnsRecord->getPrio());

                $em->persist($newRecord);
                $em->flush();
            }

        }
    }
}
