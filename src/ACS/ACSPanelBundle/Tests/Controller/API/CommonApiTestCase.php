<?php

namespace ACS\ACSPanelBundle\Tests\Controller\API;

use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class CommonApiTestCase extends CommonTestCase
{
    public static function assertJsonResponse($client)
    {
        // Check if the respense contents are json
        parent::assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }
}
