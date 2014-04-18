<?php
namespace ACS\ACSPanelBundle\Event;

final class DnsEvents
{
	const DNS_AFTER_DOMAIN_ADD = 'dns.after.domain.add';
	const DNS_AFTER_DOMAIN_UPDATE = 'dns.after.domain.update';
	const DNS_AFTER_RECORD_ADD = 'dns.after.record.add';
	const DNS_AFTER_RECORD_UPDATE = 'dns.after.record.update';
	const DNS_AFTER_RECORD_DELETE = 'dns.after.record.delete';
	const DOMAIN_BEFORE_ADD = 'domain.before.add';
}
