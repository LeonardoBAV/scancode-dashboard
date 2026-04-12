<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Product;
use App\Models\SalesRepresentative;

class ProductPolicy
{
    public function update(SalesRepresentative $salesRepresentative, Product $product): bool
    {
        return $salesRepresentative->distributor_id === $product->distributor_id;
    }
}
