<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminNotificationPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\CoreBundle\Application\Kernel;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class AdminMenuListener
{
    private const SYLIUS_MAJOR_VERSION = 1;

    private const SYLIUS_MIN_MAJOR_VERSION = 11;

    private RepositoryInterface $notificationRepository;

    public function __construct(RepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $this->getParentMenu($event);

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

    /** @phpstan-ignore-next-line */
    private function getParentMenu(MenuBuilderEvent $event): ?ItemInterface
    {
        /** @phpstan-ignore-next-line */
        if (self::SYLIUS_MAJOR_VERSION === (int) Kernel::MAJOR_VERSION && (int) Kernel::MINOR_VERSION >= self::SYLIUS_MIN_MAJOR_VERSION) {
            return $event->getMenu()->addChild('administration');
        }

        /** @phpstan-ignore-next-line */
        return $event->getMenu()->getChild('sylius.ui.administration');
    }
}
