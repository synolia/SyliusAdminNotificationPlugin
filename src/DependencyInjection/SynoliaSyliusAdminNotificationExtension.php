<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminNotificationPlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Yaml\Yaml;
use Synolia\SyliusAdminNotificationPlugin\Entity\AdminNotification;

final class SynoliaSyliusAdminNotificationExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $this->registerResources(
            'synolia_sylius_admin_notification',
            $config['driver'],
            $config['resources'],
            $container
        );

        $loader = new PhpFileLoader($container, new FileLocator(\dirname(__DIR__, 2) . '/config'));
        $loader->load('services.php');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $container->setParameter('synolia_sylius_admin_notification.model.admin_notification.class', AdminNotification::class);

        /** @var iterable $config */
        $config = Yaml::parseFile(\dirname(__DIR__, 2) . '/config/packages/app.yaml');

        foreach ($config as $packageName => $packageConfig) {
            $container->prependExtensionConfig($packageName, $packageConfig);
        }

        $this->prependDoctrineMigrations($container);
    }

    protected function getMigrationsNamespace(): string
    {
        return 'Synolia\SyliusAdminNotificationPlugin\Migrations';
    }

    protected function getMigrationsDirectory(): string
    {
        return '@SynoliaSyliusAdminNotificationPlugin/migrations';
    }

    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return [];
    }
}
