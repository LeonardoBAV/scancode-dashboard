<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Product;
use App\Models\SalesRepresentative;
use App\Models\User;

class ProductPolicy
{
    public function update(User|SalesRepresentative $auth, Product $product): bool
    {
        if (! $auth instanceof SalesRepresentative) {
            return true;
        }

        return $auth->distributor_id === $product->distributor_id;
    }
}
