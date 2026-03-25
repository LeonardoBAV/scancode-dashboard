<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\OrderItemObserver;
use Database\Factories\OrderItemFactory;
use Exception;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([OrderItemObserver::class])]
class OrderItem extends Model
{
    /** @use HasFactory<OrderItemFactory> */
    use HasFactory;

    protected $fillable = [
        'distributor_id',
        'order_id',
        'product_id',
        'price',
        'qty',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'price' => 'decimal:2',
        'qty' => 'integer',
    ];

    /**
     * @return BelongsTo<Distributor, $this>
     */
    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function canBeCreated(): bool
    {
        return $this->order->canBeUpdated();
    }

    public function canBeUpdated(): bool
    {
        return $this->order->canBeUpdated();
    }

    public function canBeDeleted(): bool
    {
        return $this->order->canBeUpdated();
    }

    public function ensureCanBeCreated(): void
    {
        if (! $this->canBeCreated()) {
            throw new Exception(__('exceptions.order_item_creating_order_not_pending'));
        }
    }

    public function ensureCanBeUpdated(): void
    {
        if (! $this->canBeUpdated()) {
            throw new Exception(__('exceptions.order_item_updating_order_not_pending'));
        }
    }

    public function ensureCanBeDeleted(): void
    {
        if (! $this->canBeDeleted()) {
            throw new Exception(__('exceptions.order_item_deleting_order_not_pending'));
        }
    }
}
