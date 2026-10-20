<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['title', 'image_url', 'target_url', 'position_type', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
