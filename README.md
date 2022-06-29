![Tests](https://github.com/synolia/SyliusAdminNotificationPlugin/workflows/CI/badge.svg?branch=master)

<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>

<h1 align="center">Admin Notification Plugin</h1>
<p align="center">Add custom notification messages in your Sylius admin panel.</p>


## How to create a notification

### Send notification using Symfony notifier
```php
<?php

declare(strict_types=1);

namespace App\Notification;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;

final class SendTestNotification
{
    private NotifierInterface $notifier;

    public function __construct(NotifierInterface $notifier) {
        $this->notifier = $notifier;
    }

    protected function send(string $title, string $content): void
    {
        $notification = (new Notification($title, ['chat/admin_notification']))
            ->content($content);

        $this->notifier->send($notification);
    }
}
```

### Send notification using monolog handler

```yaml
monolog:
    channels: ['synolia_sylius_admin_notification']
    handlers:
        synolia_sylius_admin_notification:
            channels: [synolia_sylius_admin_notification]
            level: debug
            type: service
            id: Synolia\SyliusAdminNotificationPlugin\Handler\Monolog\AdminNotificationHandler
```

```php
<?php

declare(strict_types=1);

namespace App\EnvieDeFraiseBundle\Command;

use Psr\Log\LoggerInterface;

final class SendTestNotification
{
    private LoggerInterface $synoliaSyliusAdminNotificationLogger;

    public function __construct(LoggerInterface $synoliaSyliusAdminNotificationLogger) {
        $this->synoliaSyliusAdminNotificationLogger = $synoliaSyliusAdminNotificationLogger;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->synoliaSyliusAdminNotificationLogger->critical('Notification message', [
            'context_1' => 'this is the first context',
            'context_2' => [
                'key' => 'val'
            ],
        ]);
    }
}
```

### Write logs as notification

```yaml
monolog:
    handlers:
        synolia_sylius_admin_notification:
            level: debug
            type: service
            id: Synolia\SyliusAdminNotificationPlugin\Handler\Monolog\AdminNotificationHandler
        main:
            type: fingers_crossed
            action_level: error
            handler: grouped
            channels: [ "!deprecation", "!sonata"]
            excluded_http_codes: [404, 405]
            buffer_size: 50 # How many messages should be saved? Prevent memory leaks
        grouped:
            type: group
            members: [ nested, deduplicated ]
        nested:
            type: stream
            path: "%kernel.logs_dir%/%kernel.environment%.log"
            level: debug
        deduplicated:
            type: deduplication
            handler: synolia_sylius_admin_notification
```
