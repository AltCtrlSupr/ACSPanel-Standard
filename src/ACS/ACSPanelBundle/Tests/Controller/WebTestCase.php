<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BTC;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class WebTestCase extends BTC
{

    static $container;

    static protected function createClient(array $options = array(), array $server = array())
    {
        $client = parent::createClient($options, $server);

        self::$container = self::$kernel->getContainer();

        return $client;
    }

    protected function createAuthClient($user, $pass)
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => $user,
            'PHP_AUTH_PW'   => $pass,
        ));

        $this->logIn($client, $user, $pass);

        return $client;
    }

   private function logIn($client, $user, $pass, $roles = array('ROLE_SUPER_ADMIN'))
    {
        $session = $client->getContainer()->get('session');

        $firewall = 'secured_area';
        $token = new UsernamePasswordToken($user, null, $firewall, $roles);
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }
}
