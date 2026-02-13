<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alert extends Model
{
    protected $fillable = [
        'product_id', 'type', 'message', 'is_read', 
        'sent_at', 'severity'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'sent_at' => 'datetime',
    ];

    const TYPE_MIN_STOCK = 'min_stock';
    const TYPE_RUPTURE = 'rupture';
    const TYPE_EXPIRATION = 'expiration';
    const TYPE_OVERSTOCK = 'overstock';

    const SEVERITY_LOW = 'low';
    const SEVERITY_MEDIUM = 'medium';
    const SEVERITY_HIGH = 'high';
    const SEVERITY_CRITICAL = 'critical';

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
