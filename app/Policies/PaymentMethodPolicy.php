<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PaymentMethod;
use App\Models\SalesRepresentative;

class PaymentMethodPolicy
{
    public function update(SalesRepresentative $salesRepresentative, PaymentMethod $paymentMethod): bool
    {
        return $salesRepresentative->distributor_id === $paymentMethod->distributor_id;
    }
}
