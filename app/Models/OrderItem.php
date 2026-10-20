<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'item_id', 'quantity', 'price', 'total_price', 'modifiers_json'];

    protected $casts = [
        'price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'modifiers_json' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
