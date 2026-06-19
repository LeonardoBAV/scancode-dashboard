<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Client;
use App\Models\SalesRepresentative;
use App\Models\User;

class ClientPolicy
{
    public function create(User|SalesRepresentative $auth): bool
    {
        if (! $auth instanceof SalesRepresentative) {
            return true;
        }

        return $auth->distributor_id !== null;
    }

    public function update(User|SalesRepresentative $auth, Client $client): bool
    {

        if (! $auth instanceof SalesRepresentative) {
            return true;
        }

        return $auth->distributor_id === $client->distributor_id;
    }
}
