<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Scopes\FilterDistributorByAuthSalesRepresentativeScope;
use App\Utils\QueryHelper;
use Database\Factories\ClientFactory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    /** @use HasFactory<ClientFactory> */
    use HasFactory;

    protected $fillable = [
        'distributor_id',
        'cpf_cnpj',
        'corporate_name',
        'fantasy_name',
        'email',
        'phone',
        'carrier',
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
     * @param  string  $order  "column:asc|desc"
     * @return Collection<int, Client>|LengthAwarePaginator
     */
    public static function listBy(
        array $filters = [],
        array $fields = [],
        string $order = 'corporate_name:asc',
        ?int $size = null,
    ): Collection|LengthAwarePaginator {
        $allowedColumns = [
            'id',
            'cpf_cnpj',
            'corporate_name',
            'fantasy_name',
            'email',
            'phone',
            'carrier',
            'created_at',
            'updated_at',
        ];

        $query = static::query();

        if (($filters['corporate_name'] ?? null) !== null) {
            $query->where('corporate_name', 'like', '%'.$filters['corporate_name'].'%');
        }

        if (($filters['fantasy_name'] ?? null) !== null) {
            $query->where('fantasy_name', 'like', '%'.$filters['fantasy_name'].'%');
        }

        if (($filters['email'] ?? null) !== null) {
            $query->where('email', 'like', '%'.$filters['email'].'%');
        }

        if (($filters['cpf_cnpj'] ?? null) !== null) {
            $query->where('cpf_cnpj', 'like', '%'.$filters['cpf_cnpj'].'%');
        }

        $selectColumns = array_values(array_intersect($fields, $allowedColumns));

        if ($selectColumns === []) {
            $selectColumns = [
                'id',
                'cpf_cnpj',
                'corporate_name',
                'fantasy_name',
                'email',
                'phone',
                'carrier',
                'created_at',
                'updated_at',
            ];
        }

        if (! in_array('id', $selectColumns, true)) {
            array_unshift($selectColumns, 'id');
        }

        $query->select($selectColumns);

        [$column, $direction] = QueryHelper::parseOrder($order, $allowedColumns, 'corporate_name');
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
