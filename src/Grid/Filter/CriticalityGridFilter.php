<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminNotificationPlugin\Grid\Filter;

use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

final class CriticalityGridFilter implements FilterInterface
{
    /**
     * {@inheritDoc}
     */
    public function apply(DataSourceInterface $dataSource, string $name, $data, array $options = []): void
    {
        if (!\is_array($data)) {
            return;
        }

        if (null === $data['criticality'] || '' === $data['criticality']) {
            return;
        }

        $dataSource->restrict($dataSource->getExpressionBuilder()->equals($name, $data['criticality']));
    }
}
