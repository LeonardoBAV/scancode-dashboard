<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Scopes\FilterDistributorByAuthSalesRepresentativeScope;
use App\Utils\QueryHelper;
use Database\Factories\EventFactory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    /** @use HasFactory<EventFactory> */
    use HasFactory;

    protected $fillable = [
        'distributor_id',
        'name',
        'start',
        'end',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start' => 'date',
            'end' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new FilterDistributorByAuthSalesRepresentativeScope);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @param  list<string>  $fields
     * @param  string  $order  "column:asc|desc"
     * @return Collection<int, Event>|LengthAwarePaginator
     */
    public static function listBy(
        array $filters = [],
        array $fields = [],
        string $order = 'start:asc',
        ?int $size = null,
    ): Collection|LengthAwarePaginator {
        $allowedColumns = ['id', 'name', 'start', 'end', 'created_at', 'updated_at'];

        $query = static::query();

        if (($filters['name'] ?? null) !== null) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        if (($filters['start_from'] ?? null) !== null) {
            $query->whereDate('start', '>=', $filters['start_from']);
        }

        if (($filters['start_to'] ?? null) !== null) {
            $query->whereDate('start', '<=', $filters['start_to']);
        }

        if (($filters['end_from'] ?? null) !== null) {
            $query->whereDate('end', '>=', $filters['end_from']);
        }

        if (($filters['end_to'] ?? null) !== null) {
            $query->whereDate('end', '<=', $filters['end_to']);
        }

        $selectColumns = array_values(array_intersect($fields, $allowedColumns));

        if ($selectColumns === []) {
            $selectColumns = ['id', 'name', 'start', 'end', 'created_at', 'updated_at'];
        }

        if (! in_array('id', $selectColumns, true)) {
            array_unshift($selectColumns, 'id');
        }

        $query->select($selectColumns);

        [$column, $direction] = QueryHelper::parseOrder($order, $allowedColumns, 'start');
        $query->orderBy($column, $direction);

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
