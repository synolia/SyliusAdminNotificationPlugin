<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminNotificationPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class AdminMenuListener
{
    private RepositoryInterface $notificationRepository;

    public function __construct(RepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu()->getChild('sylius.ui.administration');

        if (!$menu instanceof ItemInterface) {
            return;
        }

        $menu
            ->addChild('synolia_sylius_admin_notification.ui.notifications', [
                'route' => 'synolia_sylius_admin_notification_admin_notification_index',
            ])
           ->setAttribute('type', 'link')
           ->setLabel('synolia_sylius_admin_notification.ui.notifications')
           ->setLabelAttribute('icon', $this->getIcons())
        ;
    }

    private function getIcons(): string
    {
        /** @phpstan-ignore-next-line */
        $count = $this->notificationRepository->count([]);

        if (0 === $count) {
            return 'bell';
        }

        return 'bell yellow';
    }
}
