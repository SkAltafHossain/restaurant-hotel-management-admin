<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = ['order_id', 'room_booking_id', 'payment_method', 'transaction_id', 'amount', 'status'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function roomBooking(): BelongsTo
    {
        return $this->belongsTo(RoomBooking::class);
    }
}
