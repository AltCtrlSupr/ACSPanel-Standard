<?php
namespace ACS\ACSPanelBundle\Event;

final class MenuEvents
{
    /**
     * The user.register event is thrown before the items are added to menu
     *
     * The event listener receives an
     * ACS\ACSPanelBundle\Event\FilterMenuEvent instance.
     *
     * @var string
     */
    const ADMIN_BEFORE_ITEMS = 'menu.admin.before.items';

    /**
     * The user.register event is thrown after the items are added to menu
     *
     * The event listener receives an
     * ACS\ACSPanelBundle\Event\FilterMenuEvent instance.
     *
     * @var string
     */
    const ADMIN_AFTER_ITEMS = 'menu.admin.after.items';

    /**
     * The user.register event is thrown before the items are added to menu
     *
     * The event listener receives an
     * ACS\ACSPanelBundle\Event\FilterMenuEvent instance.
     *
     * @var string
     */
    const RESELLER_BEFORE_ITEMS = 'menu.reseller.before.items';

    /**
     * The user.register event is thrown after the items are added to menu
     *
     * The event listener receives an
     * ACS\ACSPanelBundle\Event\FilterMenuEvent instance.
     *
     * @var string
     */
    const RESELLER_AFTER_ITEMS = 'menu.reseller.after.items';

    /**
     * The user.register event is thrown before the items are added to menu
     *
     * The event listener receives an
     * ACS\ACSPanelBundle\Event\FilterMenuEvent instance.
     *
     * @var string
     */
    const USER_BEFORE_ITEMS = 'menu.user.before.items';

    /**
     * The user.register event is thrown after the items are added to menu
     *
     * The event listener receives an
     * ACS\ACSPanelBundle\Event\FilterMenuEvent instance.
     *
     * @var string
     */
    const USER_AFTER_ITEMS = 'menu.user.after.items';
}
