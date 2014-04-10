<?php

namespace ACS\ACSPanelBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class CommonTestCase extends WebTestCase
{
    public $client;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();

        static::$kernel = static::createkernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getcontainer()
            ->get('doctrine')
            ->getmanager()
        ;
    }

    protected function loadTestFixtures()
    {
        $loader = new Loader();
        $loader->loadFromDirectory('src/Picmedia/BetaBundle/Tests/DataFixtures/');
        $fixtures = $loader->getFixtures();

        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
    }

    protected function requestWithAuth($role, $client, $method, $uri, $parameters = array())
    {
        $this->client = $this->createAuthorizedClient($role);
        return $this->client->request($method, $uri, $parameters, array(), array());
    }

    protected function createAuthorizedClient($username)
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $session = $container->get('session');
        /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $container->get('fos_user.user_manager');
        /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $container->get('fos_user.security.login_manager');
        $firewallName = $container->getParameter('fos_user.firewall_name');

        $user = $userManager->findUserBy(array('username' => $username));
        $loginManager->loginUser($firewallName, $user);

        // save the login token into the session and put it in a cookie
        $container->get('session')->set('_security_' . $firewallName,
            serialize($container->get('security.context')->getToken()));
        $container->get('session')->save();
        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }
}
