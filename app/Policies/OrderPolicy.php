<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Order;
use App\Models\SalesRepresentative;
use App\Models\User;

class OrderPolicy
{
    public function create(User|SalesRepresentative $auth): bool
    {
        if (! $auth instanceof SalesRepresentative) {
            return true;
        }

        return $auth->distributor_id !== null;
    }

    public function update(User|SalesRepresentative $auth, Order $order): bool
    {
        if (! $auth instanceof SalesRepresentative) {
            return true;
        }

        return $auth->distributor_id === $order->distributor_id;
    }
}
