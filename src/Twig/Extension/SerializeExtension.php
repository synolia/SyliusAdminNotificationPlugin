<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminNotificationPlugin\Twig\Extension;

use Symfony\Component\Serializer\SerializerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class SerializeExtension extends AbstractExtension
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('serialize', [$this, 'serialize']),
        ];
    }

    public function serialize(
        array $object,
        string $format = 'json',
        array $context = ['json_encode_options' => \JSON_PRETTY_PRINT]
    ): string {
        return $this->serializer->serialize($object, $format, $context);
    }
}
