<?php

namespace ACS\ACSPanelUsersBundle\Tests\Model;

use ACS\ACSPanelUsersBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testGetHomeDir()
    {
        $test_user = new User();
        $test_user->setUsername('administrator');

        $home_dir = $test_user->getHomeDir();

        $this->assertEquals($home_dir, $test_user->getUsername());
    }
}

