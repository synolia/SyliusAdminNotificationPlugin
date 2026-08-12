<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminNotificationPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class AdminMenuListener
{
    #[AsEventListener(event: 'sylius.menu.admin.main', priority: -99)]
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $this->getParentMenu($event);

        if (!$menu instanceof ItemInterface) {
            return;
        }

        $notificationsMenu = $menu
            ->addChild('synolia_sylius_admin_notification.ui.notifications', [
                'route' => 'synolia_sylius_admin_notification_admin_notification_index',
            ])
            ->setAttribute('type', 'link')
            ->setLabel('synolia_sylius_admin_notification.ui.notifications')
        ;

        $notificationsMenu->setExtra('routes', [
            ['pattern' => '/^synolia_sylius_admin_notification_/'],
        ]);
    }

    /** @phpstan-ignore-next-line */
    private function getParentMenu(MenuBuilderEvent $event): ?ItemInterface
    {
        return $event->getMenu()->getChild('sylius.ui.administration');
    }
}
