<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminNotificationPlugin\Transport\Notification;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Notifier\Message\SentMessage;
use Symfony\Component\Notifier\Recipient\NoRecipient;
use Symfony\Component\Notifier\Transport\TransportInterface;
use Synolia\SyliusAdminNotificationPlugin\Entity\AdminNotificationInterface;

final class AdminNotificationTransport implements TransportInterface
{
    private const TRANSPORT = 'admin_notification';
    private EntityManagerInterface $entityManager;
    private FactoryInterface $notificationFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        FactoryInterface $notificationFactory
    ) {
        $this->entityManager = $entityManager;
        $this->notificationFactory = $notificationFactory;
    }

    public function __toString(): string
    {
        return self::TRANSPORT;
    }

    public function send(MessageInterface $message): ?SentMessage
    {
        return $this->doSend($message);
    }

    public function supports(MessageInterface $message): bool
    {
        return self::TRANSPORT === $message->getTransport();
    }

    private function doSend(MessageInterface $message): SentMessage
    {
        $message->getSubject();

        /** @var AdminNotificationInterface $adminNotification */
        $adminNotification = $this->notificationFactory->createNew();
        $adminNotification->setCreatedAt(new \DateTime());
        $adminNotification
            ->setLevelName($message->getNotification()->getImportance())
            ->setChannel(implode(',', $message->getNotification()->getChannels(new NoRecipient())))
            ->setMessage($message->getSubject())
            ->setContext([
                'content' => $message->getNotification()->getContent(),
            ])
        ;

        $this->entityManager->persist($adminNotification);
        $this->entityManager->flush();

        // Create an instance of SentMessage that should be returned to respect the contract.
        $sentMessage = new SentMessage($message, (string) $this);

        // Suppose the API returns the id of the transaction.
        $sentMessage->setMessageId('1');

        // 💡 for your information this object will be passed to an event.
        return $sentMessage;
    }
}
