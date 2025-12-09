<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\OrderItem;

class OrderItemObserver
{
    public function creating(OrderItem $orderItem): void
    {
        $orderItem->ensureCanBeCreated();
    }

    public function updating(OrderItem $orderItem): void
    {
        $orderItem->ensureCanBeUpdated();
    }

    public function deleting(OrderItem $orderItem): void
    {
        $orderItem->ensureCanBeDeleted();
    }
}
