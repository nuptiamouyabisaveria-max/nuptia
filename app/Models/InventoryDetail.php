<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryDetail extends Model
{
    protected $fillable = [
        'inventory_id', 'product_id', 'counted_quantity',
        'system_quantity', 'variance', 'justification'
    ];

    protected $casts = [
        'counted_quantity' => 'integer',
        'system_quantity' => 'integer',
        'variance' => 'integer',
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
