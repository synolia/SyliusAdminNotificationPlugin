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
     * In our example, we don't have to pass credentials
     * check https://github.com/symfony/linked-in-notifier/blob/5.3/LinkedInTransportFactory.php#L24
     * if you need to parse the DSN.
     */
    public function create(Dsn $dsn): TransportInterface
    {
        return new AdminNotificationTransport($this->entityManager, $this->notificationFactory);
    }

    public function supports(Dsn $dsn): bool
    {
        return \in_array($dsn->getScheme(), $this->getSupportedSchemes());
    }

    private function getSupportedSchemes(): array
    {
        return ['adminnotification'];
    }
}
