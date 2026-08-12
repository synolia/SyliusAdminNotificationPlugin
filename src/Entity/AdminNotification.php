<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminNotificationPlugin\Entity;

use Sylius\Component\Resource\Model\TimestampableTrait;

class AdminNotification implements AdminNotificationInterface
{
    use TimestampableTrait;

    protected ?int $id = null;

    protected string $channel = '';

    protected string $levelName = '';

    protected string $message = '';

    /** @var array<string, mixed> */
    protected array $context = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $title): self
    {
        $this->message = $title;

        return $this;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function getLevelName(): string
    {
        return $this->levelName;
    }

    public function setLevelName(string $levelName): self
    {
        $this->levelName = $levelName;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function setContext(array $context): self
    {
        $this->context = $context;

        return $this;
    }
}
