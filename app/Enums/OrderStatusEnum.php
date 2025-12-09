<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatusEnum: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::COMPLETED => 'primary',
            self::CANCELLED => 'danger',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('enums.order_status.pending'),
            self::COMPLETED => __('enums.order_status.completed'),
            self::CANCELLED => __('enums.order_status.cancelled'),
        };
    }
}
