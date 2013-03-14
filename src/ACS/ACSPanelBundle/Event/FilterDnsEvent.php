<?php
namespace ACS\ACSPanelBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class FilterDnsEvent extends Event
{
	protected $dnsDomain;
	protected $em;

	public function __construct($dnsdomain, $em)
	{
		$this->dnsDomain = $dnsdomain;
		$this->em = $em;
	}

	public function getDnsDomain()
	{
		return $this->dnsDomain;
	}

	public function getEm()
	{
			  return $this->em;
	}
}
