<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /* mass assignable order fields */
    protected $fillable = ['user_id', 'order_number', 'subtotal', 'discount', 'total', 'status', 'shipping_address'];

    /* order belongs to customer */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /* order has many items */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}