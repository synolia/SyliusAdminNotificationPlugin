<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminNotificationPlugin\Form\Type\Grid\Filter;

use Psr\Log\LogLevel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

final class CriticalityGridFilterType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'criticality',
            ChoiceType::class,
            [
                'placeholder' => '',
                'choices' => [
                    LogLevel::EMERGENCY => LogLevel::EMERGENCY,
                    LogLevel::ALERT => LogLevel::ALERT,
                    LogLevel::CRITICAL => LogLevel::CRITICAL,
                    LogLevel::ERROR => LogLevel::ERROR,
                    LogLevel::WARNING => LogLevel::WARNING,
                    LogLevel::NOTICE => LogLevel::NOTICE,
                ],
            ]
        );
    }
}
