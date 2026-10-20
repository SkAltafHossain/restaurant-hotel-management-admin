<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TableBooking extends Model
{
    protected $fillable = ['user_id', 'table_number', 'booking_date', 'time_slot', 'guest_count', 'status'];

    protected $casts = [
        'booking_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
