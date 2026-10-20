<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'type', 'slug', 'image', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->name);
            }
        });

        static::updating(function ($category) {
            if (empty($category->slug) || $category->isDirty('name')) {
                $category->slug = static::generateUniqueSlug($category->name, $category->id);
            }
        });
    }

    private static function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->when($excludeId, fn ($query) => $query->where('id', '!=', $excludeId))->exists()) {
            $slug = $originalSlug.'-'.$counter++;
        }

        return $slug;
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
