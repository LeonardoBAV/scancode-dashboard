<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Scopes\FilterDistributorByAuthSalesRepresentativeScope;
use App\Utils\QueryHelper;
use Database\Factories\ProductFactory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'distributor_id',
        'sku',
        'barcode',
        'name',
        'price',
        'product_category_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new FilterDistributorByAuthSalesRepresentativeScope);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @param  list<string>  $fields
     * @param  list<string>  $relations  Eloquent relation names allowed for {@see static::allowedListByRelations()}
     * @param  string  $order  "column:asc|desc"
     * @return Collection<int, Product>|LengthAwarePaginator
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
            'sku',
            'barcode',
            'name',
            'price',
            'product_category_id',
            'created_at',
            'updated_at',
        ];

        $allowedRelations = [
            'distributor',
            'productCategory',
            'orderItems',
        ];

        $query = static::query();

        if (($filters['name'] ?? null) !== null) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        if (($filters['sku'] ?? null) !== null) {
            $query->where('sku', 'like', '%'.$filters['sku'].'%');
        }

        if (($filters['barcode'] ?? null) !== null) {
            $query->where('barcode', 'like', '%'.$filters['barcode'].'%');
        }

        if (($filters['product_category_id'] ?? null) !== null) {
            $query->where('product_category_id', $filters['product_category_id']);
        }

        if (($filters['price_from'] ?? null) !== null) {
            $query->where('price', '>=', $filters['price_from']);
        }

        if (($filters['price_to'] ?? null) !== null) {
            $query->where('price', '<=', $filters['price_to']);
        }

        $selectColumns = array_values(array_intersect($fields, $allowedColumns));

        if ($selectColumns === []) {
            $selectColumns = [
                'id',
                'sku',
                'barcode',
                'name',
                'price',
                'product_category_id',
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
     * @return BelongsTo<ProductCategory, $this>
     */
    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
