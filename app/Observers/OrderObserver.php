<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    public function updating(Order $order): void
    {
        $order->ensureCanBeUpdated();
    }

    public function deleting(Order $order): void
    {
        $order->ensureCanBeDeleted();
    }
}
