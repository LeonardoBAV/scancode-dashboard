<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PaymentMethod;
use App\Models\SalesRepresentative;
use App\Models\User;

class PaymentMethodPolicy
{
    public function create(User|SalesRepresentative $auth): bool
    {
        if (! $auth instanceof SalesRepresentative) {
            return false;
        }

        return $auth->distributor_id !== null;
    }

    public function update(User|SalesRepresentative $auth, PaymentMethod $paymentMethod): bool
    {
        if (! $auth instanceof SalesRepresentative) {
            return true;
        }

        return $auth->distributor_id === $paymentMethod->distributor_id;
    }
}
