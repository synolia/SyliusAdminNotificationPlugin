<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminNotificationPlugin\Handler\Monolog;

use Doctrine\ORM\EntityManagerInterface;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\CacheItem;
use Synolia\SyliusAdminNotificationPlugin\Entity\AdminNotificationInterface;

final class AdminNotificationHandler extends AbstractProcessingHandler
{
    private const CACHE_KEY = 'synolia_sylius_notification.notifications';

    private const MAX_VALUE = 1;

    private EntityManagerInterface $entityManager;

    private FactoryInterface $notificationFactory;

    private ArrayAdapter $cache;

    private HtmlFormatter $htmlFormatter;

    public function __construct(
        FactoryInterface $notificationFactory,
        EntityManagerInterface $entityManager,
        HtmlFormatter $htmlFormatter,
    ) {
        parent::__construct();
        $this->notificationFactory = $notificationFactory;
        $this->entityManager = $entityManager;
        $this->htmlFormatter = $htmlFormatter;

        $this->cache = new ArrayAdapter();
    }

    protected function write(LogRecord $record): void
    {
        $cachedNotifications = $this->cacheValue($record);
        $this->cache->save($cachedNotifications);
        $this->flushCache($cachedNotifications);
    }

    private function cacheValue(LogRecord $record): CacheItem
    {
        $cachedNotifications = $this->cache->getItem(self::CACHE_KEY);

        if (!$cachedNotifications->isHit()) {
            $cachedNotifications->set([$record]);

            return $cachedNotifications;
        }

        /** @var array<array-key, LogRecord> $cachedValues */
        $cachedValues = $cachedNotifications->get();
        $cachedValues[] = $record;

        $cachedNotifications->set($cachedValues);

        return $cachedNotifications;
    }

    private function flushCache(CacheItem $cachedNotifications): void
    {
        /** @var array<array-key, LogRecord> $cachedValue */
        $cachedValue = $cachedNotifications->get();

        // OR check last update
        if (\count($cachedValue) < self::MAX_VALUE) {
            return;
        }

        foreach ($cachedValue as $record) {
            /** @var AdminNotificationInterface $adminNotification */
            $adminNotification = $this->notificationFactory->createNew();
            $adminNotification->setCreatedAt($record->datetime);
            $adminNotification
                ->setLevelName($record->level->getName())
                ->setChannel($record->channel)
                ->setMessage($record->message)
                ->setContext(['html' => $this->htmlFormatter->format($record)])
            ;

            $this->entityManager->persist($adminNotification);
        }

        $this->entityManager->flush();
        $this->cache->delete(self::CACHE_KEY);
    }
}
