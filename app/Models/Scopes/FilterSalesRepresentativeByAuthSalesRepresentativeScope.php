<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Models\SalesRepresentative;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

/**
 * Adds WHERE sales_representative_id = <seller's id> when the authenticated user
 * is a SalesRepresentative. No-op for User (Filament), CLI, and jobs.
 */
class FilterSalesRepresentativeByAuthSalesRepresentativeScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        if (! $user instanceof SalesRepresentative) {
            return;
        }

        $builder->where(
            $model->getTable().'.sales_representative_id',
            $user->id,
        );
    }
}
