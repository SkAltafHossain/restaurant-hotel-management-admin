<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    protected $fillable = ['item_name', 'sku', 'current_stock', 'unit', 'low_alert_qty', 'supplier_name'];

    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->low_alert_qty;
    }
}
