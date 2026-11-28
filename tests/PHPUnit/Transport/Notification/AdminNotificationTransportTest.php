<?php

declare(strict_types=1);

namespace Tests\Synolia\SyliusAdminNotificationPlugin\PHPUnit\Transport\Notification;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Notification\Notification;
use Synolia\SyliusAdminNotificationPlugin\Entity\AdminNotificationInterface;

/**
 * @internal
 */
final class AdminNotificationTransportTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    private RepositoryInterface $adminNotificationRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->adminNotificationRepository = self::getContainer()->get('synolia_sylius_admin_notification.repository.notification');
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->entityManager->beginTransaction();
    }

    protected function tearDown(): void
    {
        $this->entityManager->rollback();
        parent::tearDown();
    }

    public function testChatMessageIsSavedAsAdminNotification(): void
    {
        self::assertSame(0, $this->adminNotificationRepository->count([]));

        $notification = (new Notification('Stock is running low', ['chat']))
            ->content('Only 2 items left')
            ->importance(Notification::IMPORTANCE_HIGH)
        ;
        $message = ChatMessage::fromNotification($notification)->transport('admin_notification');

        /** @var ChatterInterface $chatter */
        $chatter = self::getContainer()->get(ChatterInterface::class);
        $chatter->send($message);

        /** @var AdminNotificationInterface|null $adminNotification */
        $adminNotification = $this->adminNotificationRepository->findOneBy([]);
        self::assertNotNull($adminNotification);
        self::assertSame('Stock is running low', $adminNotification->getMessage());
        self::assertSame(Notification::IMPORTANCE_HIGH, $adminNotification->getLevelName());
        self::assertSame('chat', $adminNotification->getChannel());
        self::assertSame(['content' => 'Only 2 items left'], $adminNotification->getContext());
    }
}
