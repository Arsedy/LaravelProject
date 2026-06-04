<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'zip_code',
        'subtotal',
        'shipping_price',
        'total',
        'shipping_method',
        'payment_method',
        'status',
    ];

    public const STATUSES = [
        'New',
        'Accepted',
        'Cancelled',
        'Onshipping',
        'Completed',
    ];

    /**
     * Get the user who placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the ordered items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
