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
        //$client = self::createAuthClient('superadmin','1234');

        self::$container = self::$kernel->getContainer();

        return $client;
    }

    static protected function get($serviceId)
    {
        return self::$container->get($serviceId);
    }

    protected function createAuthClient($user, $pass)
    {
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => $user,
            'PHP_AUTH_PW'   => $pass,
        ));

        // $client = self::logIn($client, $user, $pass);

        $securityContext = self::get('security.context');
        $userProvider = $this->get('fos_user.user_provider.username');

        $loginProvider = $this->get('fos_user.security.login_manager');
        $user = $userProvider->loadUserByUsername($user);
        $loginProvider->loginUser('main',$user);

        $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());
        $securityContext->setToken($token);

        $client->followRedirects();

        return $client;
    }

}
