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
        /** @var array{driver: string, resources: array<string, mixed>} $config */
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

        $configPath = \dirname(__DIR__, 2) . '/config';
        /** @var array<string, array<string, mixed>> $config */
        $config = Yaml::parseFile($configPath . '/packages/app.yaml');

        foreach ($config as $packageName => $packageConfig) {
            $container->prependExtensionConfig($packageName, $packageConfig);
        }

        $this->prependTwigHooks($container, $configPath . '/twig_hooks');

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

    /**
     * @return array<string>
     */
    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return [];
    }

    private function prependTwigHooks(ContainerBuilder $container, string $configPath): void
    {
        if (!is_dir($configPath)) {
            return;
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($configPath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            if ('yaml' === $file->getExtension()) {
                /** @var array<string, mixed> $config */
                $config = Yaml::parseFile($file->getPathname());
                if (isset($config['sylius_twig_hooks']) && \is_array($config['sylius_twig_hooks'])) {
                    /** @var array<string, mixed> $hooks */
                    $hooks = $config['sylius_twig_hooks'];
                    $container->prependExtensionConfig('sylius_twig_hooks', $hooks);
                }
            }
        }
    }
}
