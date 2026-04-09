<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Scopes\FilterDistributorByAuthSalesRepresentativeScope;
use App\Utils\QueryHelper;
use Database\Factories\PaymentMethodFactory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    /** @use HasFactory<PaymentMethodFactory> */
    use HasFactory;

    protected $fillable = [
        'distributor_id',
        'name',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new FilterDistributorByAuthSalesRepresentativeScope);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @param  list<string>  $fields
     * @param  list<string>  $relations
     * @param  string  $order  "column:asc|desc"
     * @return Collection<int, PaymentMethod>|LengthAwarePaginator
     */
    public static function listBy(
        array $filters = [],
        array $fields = [],
        array $relations = [],
        string $order = 'name:asc',
        ?int $size = null,
    ): Collection|LengthAwarePaginator {
        $allowedColumns = [
            'id',
            'name',
            'created_at',
            'updated_at',
        ];

        $allowedRelations = [
            'distributor',
            'orders',
        ];

        $query = static::query();

        if (($filters['name'] ?? null) !== null) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        $selectColumns = array_values(array_intersect($fields, $allowedColumns));

        if ($selectColumns === []) {
            $selectColumns = [
                'id',
                'name',
                'created_at',
                'updated_at',
            ];
        }

        if (! in_array('id', $selectColumns, true)) {
            array_unshift($selectColumns, 'id');
        }

        $query->select($selectColumns);

        [$column, $direction] = QueryHelper::parseOrder($order, $allowedColumns, 'name');
        $query->orderBy($column, $direction);

        $eagerRelations = array_values(array_intersect($relations, $allowedRelations));

        if ($eagerRelations !== []) {
            $query->with($eagerRelations);
        }

        return $size === null
            ? $query->get()
            : $query->paginate($size);
    }

    /**
     * @return BelongsTo<Distributor, $this>
     */
    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
