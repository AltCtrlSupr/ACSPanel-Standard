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

    private $security;

    private $securityAuthorization;

    private $settings_manager;

    private $active_theme;

    public function __construct($event_dispatcher, Router $router, $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    public function setActiveTheme($active_theme)
    {
        $this->active_theme = $active_theme;
    }

    public function setSecurity($security)
    {
        $this->security = $security;
    }

    public function setSecurityAuthorization($securityAuthorization)
    {
        $this->securityAuthorization = $securityAuthorization;
    }

    public function setSettingsManager($manager)
    {
        $this->setting_manager = $manager;
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

        $settings_manager = $this->setting_manager;
        $security = $this->security;

        $user = null;
        if($security->getToken()){
            $user = $security->getToken()->getUser();
        }

        if($user && 'anon.' != $user){
            $language = $settings_manager->getUserSetting('user_language',$user);

            if($language){
                $request->setLocale($language);
            }

        }

    }

    public function switchUserTheme(GetResponseEvent $event)
    {
        $activeTheme = $this->active_theme;
        $settings_manager = $this->setting_manager;
        $security = $this->security;

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
        $security = $this->security;

        $user = null;
        if($security->getToken()){
            $user = $security->getToken()->getUser();
        }

        if ( ($security->getToken() ) && ( $this->securityAuthorization->isGranted('IS_AUTHENTICATED_FULLY') ) ) {
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
