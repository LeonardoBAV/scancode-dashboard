<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    public function creating(Order $order): void
    {
        $order->buyer_name = $order->client?->buyer_name;
        $order->buyer_phone = $order->client?->buyer_contact;
    }

    public function updating(Order $order): void
    {
        $order->ensureCanBeUpdated();
    }

    public function deleting(Order $order): void
    {
        $order->ensureCanBeDeleted();
    }
}
