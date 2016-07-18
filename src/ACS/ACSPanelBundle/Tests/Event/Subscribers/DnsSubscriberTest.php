<?php

namespace ACS\ACSPanelBundle\Tests\Event\Subscribers;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;


class DnsSubscriberTest extends CommonTestCase
{
    public function testIncrementSOA()
    {
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
    }
}

