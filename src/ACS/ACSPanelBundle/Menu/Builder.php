<?php
// src/ACS/ACSPanelBundle/Menu/Builder.php
namespace ACS\ACSPanelBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

use ACS\ACSPanelBundle\Event\FilterMenuEvent;

use ACS\ACSPanelBundle\Event\MenuEvents;

class Builder extends ContainerAware
{
    /**
     * Builds the superadmin menu
     * @param FactoryInterface $factory
     * @param array $options
     * @return type
     */
    public function superadminMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $this->container->get('event_dispatcher')->dispatch(MenuEvents::ADMIN_BEFORE_ITEMS , new FilterMenuEvent($menu));

        $this->container->get('event_dispatcher')->dispatch(MenuEvents::ADMIN_AFTER_ITEMS , new FilterMenuEvent($menu));
        return $menu;
    }

    public function userMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $this->container->get('event_dispatcher')->dispatch(MenuEvents::USER_BEFORE_ITEMS , new FilterMenuEvent($menu));

        $this->container->get('event_dispatcher')->dispatch(MenuEvents::USER_AFTER_ITEMS , new FilterMenuEvent($menu));

        return $menu;
    }

    public function resellerMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $this->container->get('event_dispatcher')->dispatch(MenuEvents::RESELLER_BEFORE_ITEMS , new FilterMenuEvent($menu));

        $this->container->get('event_dispatcher')->dispatch(MenuEvents::RESELLER_AFTER_ITEMS , new FilterMenuEvent($menu));

        return $menu;
    }


}
