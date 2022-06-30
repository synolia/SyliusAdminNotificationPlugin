<?php

declare(strict_types=1);

namespace Tests\Synolia\SyliusAdminNotificationPlugin\PHPUnit\Handler\Monolog;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Synolia\SyliusAdminNotificationPlugin\Entity\AdminNotificationInterface;

/**
 * @internal
 */
final class AdminNotificationHandlerTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private RepositoryInterface $adminNotificationRepository;

    protected function setUp(): void
    {
        static::bootKernel();

        $this->adminNotificationRepository = self::getContainer()->get('synolia_sylius_admin_notification.repository.notification');
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->entityManager->beginTransaction();
    }

    protected function tearDown(): void
    {
        $this->entityManager->rollback();
        parent::tearDown();
    }

    public function testNotificationIsSaved(): void
    {
        self::assertEquals(0, $this->adminNotificationRepository->count([]));

        /** @var LoggerInterface $logger */
        $logger = self::getContainer()->get('monolog.logger.synolia_sylius_admin_notification');
        $logger->critical('test critical', []);

        self::assertEquals(1, $this->adminNotificationRepository->count([]));
    }

    /**
     * @dataProvider criticalityDataProvider
     */
    public function testNotificationHasGoodCriticality(string $criticality): void
    {
        $repository = self::getContainer()->get('synolia_sylius_admin_notification.repository.notification');

        /** @var LoggerInterface $logger */
        $logger = self::getContainer()->get('monolog.logger.synolia_sylius_admin_notification');
        $logger->{$criticality}('test critical', []);

        /** @var AdminNotificationInterface $notification */
        $notification = $repository->findOneBy([]);
        self::assertEquals(mb_strtoupper($criticality), $notification->getLevelName());
    }

    public function criticalityDataProvider(): \Generator
    {
        yield [LogLevel::CRITICAL];
        yield [LogLevel::ALERT];
        yield [LogLevel::DEBUG];
        yield [LogLevel::EMERGENCY];
        yield [LogLevel::ERROR];
        yield [LogLevel::INFO];
        yield [LogLevel::NOTICE];
        yield [LogLevel::WARNING];
    }
}
