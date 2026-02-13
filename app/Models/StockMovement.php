<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id', 'user_id', 'type', 'quantity',
        'reason', 'reference', 'date'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'date' => 'datetime',
    ];

    const TYPE_ENTRY = 'entry';
    const TYPE_EXIT = 'exit';

    const REASON_PURCHASE = 'purchase';
    const REASON_RETURN = 'return';
    const REASON_CORRECTION = 'correction';
    const REASON_SALE = 'sale';
    const REASON_LOSS = 'loss';
    const REASON_DAMAGE = 'damage';
    const REASON_EXPIRATION = 'expiration';

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
