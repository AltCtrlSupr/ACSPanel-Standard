<?php
namespace ACS\ACSPanelBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

use ACS\ACSPanelBundle\Event\FilterMenuEvent;

use ACS\ACSPanelBundle\Event\MenuEvents;

class Builder extends ContainerAware
{
	private function createRootMenu($factory)
    {
        $menu = $factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'sidebar-menu',
            ),
        ));

        return $menu;
    }

    /**
     * Builds the superadmin menu
     * @param FactoryInterface $factory
     * @param array $options
     * @return type
     */
    public function superadminMenu(FactoryInterface $factory, array $options)
    {
        $menu = $this->createRootMenu($factory);

        $this->container->get('event_dispatcher')->dispatch(MenuEvents::ADMIN_BEFORE_ITEMS , new FilterMenuEvent($menu));

        $this->container->get('event_dispatcher')->dispatch(MenuEvents::ADMIN_AFTER_ITEMS , new FilterMenuEvent($menu));
        return $menu;
    }

    public function userMenu(FactoryInterface $factory, array $options)
    {
        $menu = $this->createRootMenu($factory);

        $this->container->get('event_dispatcher')->dispatch(MenuEvents::USER_BEFORE_ITEMS , new FilterMenuEvent($menu));

        $this->container->get('event_dispatcher')->dispatch(MenuEvents::USER_AFTER_ITEMS , new FilterMenuEvent($menu));

        return $menu;
    }

    public function resellerMenu(FactoryInterface $factory, array $options)
    {
        $menu = $this->createRootMenu($factory);

        $this->container->get('event_dispatcher')->dispatch(MenuEvents::RESELLER_BEFORE_ITEMS , new FilterMenuEvent($menu));

        $this->container->get('event_dispatcher')->dispatch(MenuEvents::RESELLER_AFTER_ITEMS , new FilterMenuEvent($menu));

        return $menu;
    }
}
