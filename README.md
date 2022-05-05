# SyliusAdminNotificationPlugin

## Notifications

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
