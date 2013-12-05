<?php

namespace ACS\ACSPanelBundle\Event\Subscribers;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class KernelSubscriber implements EventSubscriberInterface
{
    private $router;

    public function __construct($event_dispatcher, Router $router, $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    static public function getSubscribedEvents()
    {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => array(
                array(
                    'switchUserLanguage', 0
                )
                ,array(
                    'switchUserTheme', 1,
                )
                ,array(
                    'forcePasswordUpdate', 1,
                )
            ),
        );
    }

    /**
     * Sets user language from database configuration
     */
    public function switchUserLanguage(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }

        if(!$kernel)
            return;

        $settings_manager = $kernel->getContainer()->get('acs.setting_manager');
        $security = $kernel->getContainer()->get('security.context');

        $user = null;
        if($security->getToken()){
            $user = $security->getToken()->getUser();
        }

        if($user && 'anon.' != $user){
            $language = $settings_manager->getUserSetting('user_language',$user);

            if($language){
                $request->setLocale($language);
                /*if ($locale = $request->attributes->get('_locale')) {
                    $request->getSession()->set('_locale', $locale);
                } else {
                    $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
                }*/
            }

        }

    }

    public function switchUserTheme(GetResponseEvent $event)
    {
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }

        if(!$kernel)
            return;

        $container = $kernel->getContainer();

        $activeTheme = $container->get('liip_theme.active_theme');


        $settings_manager = $kernel->getContainer()->get('acs.setting_manager');
        $security = $kernel->getContainer()->get('security.context');

        $user = null;
        if($security->getToken()){
            $user = $security->getToken()->getUser();
        }

        if($user && 'anon.' != $user){
            $theme = $settings_manager->getUserSetting('user_theme',$user);
            if($theme){
                $activeTheme->setName($theme);
            }

        }
    }

    public function forcePasswordUpdate(GetResponseEvent $event)
    {
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }

        if(!$kernel)
            return;

        $container = $kernel->getContainer();
        $security = $kernel->getContainer()->get('security.context');

        $user = null;
        if($security->getToken()){
            $user = $security->getToken()->getUser();
        }

        if ( ($security->getToken() ) && ( $security->isGranted('IS_AUTHENTICATED_FULLY') ) ) {
            $route_name = $event->getRequest()->get('_route');
            if ($route_name != 'fos_user_change_password') {

                // If the pasword changed at is not set we force to change password screen
                if(!$user->getPasswordChangedAt()){
                    $response = new RedirectResponse($this->router->generate('fos_user_change_password'));
                    $this->session->getFlashBag()->set('error', "Your password hash expired. Please change it");
                    $event->setResponse($response);
                }
            }
        }

    }

}
