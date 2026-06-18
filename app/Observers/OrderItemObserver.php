<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\OrderItem;

class OrderItemObserver
{
    public function creating(OrderItem $orderItem): void
    {
        $orderItem->ensureCanBeCreated();

        $this->syncProductSnapshot($orderItem);
    }

    public function updating(OrderItem $orderItem): void
    {
        $orderItem->ensureCanBeUpdated();

        $this->syncProductSnapshot($orderItem);
    }

    public function deleting(OrderItem $orderItem): void
    {
        $orderItem->ensureCanBeDeleted();
    }

    private function syncProductSnapshot(OrderItem $orderItem): void
    {
        $product = $orderItem->product()->first();

        $orderItem->product_name = $product?->name;
    }
}
