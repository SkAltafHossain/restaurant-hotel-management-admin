<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = ['category_id', 'room_number', 'floor', 'price_per_night', 'max_adults', 'max_children', 'status'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function roomBookings(): HasMany
    {
        return $this->hasMany(RoomBooking::class);
    }
}
