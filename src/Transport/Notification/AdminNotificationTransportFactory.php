<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminNotificationPlugin\Transport\Notification;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Notifier\Transport\Dsn;
use Symfony\Component\Notifier\Transport\TransportFactoryInterface;
use Symfony\Component\Notifier\Transport\TransportInterface;

final class AdminNotificationTransportFactory implements TransportFactoryInterface
{
    private EntityManagerInterface $entityManager;
    private FactoryInterface $notificationFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        FactoryInterface $notificationFactory
    ) {
        $this->entityManager = $entityManager;
        $this->notificationFactory = $notificationFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function create(Dsn $dsn): TransportInterface
    {
        return new AdminNotificationTransport($this->entityManager, $this->notificationFactory);
    }

    public function supports(Dsn $dsn): bool
    {
        return 'adminnotification' === $dsn->getScheme();
    }
}
