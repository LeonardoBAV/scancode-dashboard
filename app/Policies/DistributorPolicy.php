<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Distributor;
use App\Models\Staff;
use App\Models\User;

class DistributorPolicy
{
    public function viewAny(User|Staff $auth): bool
    {
        return $auth instanceof Staff;
    }

    public function view(User|Staff $auth, Distributor $distributor): bool
    {
        return $auth instanceof Staff;
    }

    public function update(User|Staff $auth, Distributor $distributor): bool
    {
        if ($auth instanceof Staff) {
            return true;
        }

        return $auth->canAccessTenant($distributor);
    }
}
