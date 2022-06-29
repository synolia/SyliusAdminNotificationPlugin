<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminNotificationPlugin\Twig\Extension;

use Sylius\Component\Resource\Repository\RepositoryInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class NotificationWidgetExtension extends AbstractExtension
{
    private RepositoryInterface $notificationRepository;

    public function __construct(RepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'synolia_sylius_render_notifications_widget',
                [$this, 'renderWidget'],
                [
                    'needs_environment' => true,
                    'is_safe' => ['html'],
                ]
            ),
        ];
    }

    public function renderWidget(Environment $environment): string
    {
        return $environment->render('@SynoliaSyliusAdminNotificationPlugin/Notifications/_notification_widget.html.twig', [
            /** @phpstan-ignore-next-line  */
            'count' => $this->notificationRepository->count([]),
            'resources' => $this->notificationRepository->findBy([], ['createdAt' => 'DESC'], 5),
        ]);
    }
}
