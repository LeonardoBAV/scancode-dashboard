<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Observers\OrderObserver;
use Database\Factories\OrderFactory;
use Exception;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([OrderObserver::class])]
class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'distributor_id',
        'event_id',
        'notes',
        'buyer_name',
        'buyer_phone',
        'client_id',
        'sales_representative_id',
        'payment_method_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => OrderStatusEnum::class,
    ];

    /**
     * @return BelongsTo<Distributor, $this>
     */
    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }

    /**
     * @return BelongsTo<Event, $this>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return BelongsTo<SalesRepresentative, $this>
     */
    public function salesRepresentative(): BelongsTo
    {
        return $this->belongsTo(SalesRepresentative::class);
    }

    /**
     * @return BelongsTo<PaymentMethod, $this>
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isPending(): bool
    {
        return $this->status === OrderStatusEnum::PENDING;
    }

    public function isCompleted(): bool
    {
        return $this->status === OrderStatusEnum::COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === OrderStatusEnum::CANCELLED;
    }

    public function canBeDeleted(): bool
    {
        return $this->isPending();
    }

    public function canBeUpdated(): bool
    {
        return $this->isPending();
    }

    public function ensureCanBeDeleted(): void
    {
        if (! $this->canBeDeleted()) {
            throw new Exception(__('exceptions.order_status_deleting_not_pending'));
        }
    }

    public function ensureCanBeUpdated(): void
    {
        if (! $this->canBeUpdated()) {
            throw new Exception(__('exceptions.order_status_updating_not_pending'));
        }
    }

    public function toComplete(): void
    {
        $this->status = OrderStatusEnum::COMPLETED;
        $this->saveQuietly();
    }

    public function toCancel(): void
    {
        $this->status = OrderStatusEnum::CANCELLED;
        $this->saveQuietly();
    }

    public function toPending(): void
    {
        $this->status = OrderStatusEnum::PENDING;
        $this->saveQuietly();
    }
}
