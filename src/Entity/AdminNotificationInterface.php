<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface AdminNotificationInterface extends ResourceInterface, TimestampableInterface
{
    public function getMessage(): string;

    public function setMessage(string $title): self;

    public function getChannel(): string;

    public function setChannel(string $channel): self;

    public function getLevelName(): string;

    public function setLevelName(string $levelName): self;

    public function getContext(): array;

    public function setContext(array $context): self;
}
