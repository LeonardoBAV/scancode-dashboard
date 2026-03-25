<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Distributor;
use App\Models\User;

class DistributorPolicy
{
    public function update(User $user, Distributor $distributor): bool
    {
        return $user->canAccessTenant($distributor);
    }
}
