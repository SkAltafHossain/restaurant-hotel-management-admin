<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomBooking extends Model
{
    protected $fillable = ['user_id', 'room_id', 'check_in_date', 'check_out_date', 'total_guests', 'total_price', 'payment_status', 'booking_status', 'guest_name', 'guest_phone', 'guest_id_proof_type', 'guest_id_proof_number', 'guest_id_proof_image'];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
