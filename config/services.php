<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Synolia\SyliusAdminNotificationPlugin\Form\Type\Grid\Filter\CriticalityGridFilterType;
use Synolia\SyliusAdminNotificationPlugin\Grid\Filter\CriticalityGridFilter;
use Synolia\SyliusAdminNotificationPlugin\Handler\Monolog\AdminNotificationHandler;
use Synolia\SyliusAdminNotificationPlugin\Menu\AdminMenuListener;
use Synolia\SyliusAdminNotificationPlugin\Transport\Notification\AdminNotificationTransportFactory;

return static function (ContainerConfigurator $configurator): void {
    $configurator
        ->services()
            ->defaults()
                ->autowire()
                ->autoconfigure()
            ->load('Synolia\SyliusAdminNotificationPlugin\\', '../src')
            ->exclude([
                '../src/DependencyInjection',
                '../src/Entity',
                '../src/SynoliaSyliusAdminNotificationPlugin.php',
            ])
            ->set(AdminNotificationHandler::class, AdminNotificationHandler::class)
                ->arg('$htmlFormatter', service('monolog.formatter.html'))
            ->set(AdminNotificationTransportFactory::class)
                ->parent('notifier.transport_factory.abstract')
                ->arg('$entityManager', service('doctrine.orm.entity_manager'))
                ->arg('$notificationFactory', service('synolia_sylius_admin_notification.factory.notification'))
                ->tag('chatter.transport_factory')
            ->set(AdminMenuListener::class)
                ->tag('kernel.event_listener', ['event' => 'sylius.menu.admin.main', 'method' => 'addAdminMenuItems'])
            ->set(CriticalityGridFilter::class)
                ->tag('sylius.grid_filter', ['type' => 'criticality', 'form_type' => CriticalityGridFilterType::class])
    ;
};
