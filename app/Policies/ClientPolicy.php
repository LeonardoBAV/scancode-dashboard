<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Client;
use App\Models\SalesRepresentative;

class ClientPolicy
{
    public function update(SalesRepresentative $salesRepresentative, Client $client): bool
    {
        return $salesRepresentative->distributor_id === $client->distributor_id;
    }
}
