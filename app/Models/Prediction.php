<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediction extends Model
{
    protected $fillable = [
        'product_id', 'method', 'period_days', 'predicted_quantity',
        'rupture_risk', 'recommendation', 'confidence_score', 
        'prediction_date', 'forecast_data'
    ];

    protected $casts = [
        'predicted_quantity' => 'integer',
        'rupture_risk' => 'float',
        'confidence_score' => 'float',
        'prediction_date' => 'datetime',
        'forecast_data' => 'json',
    ];

    const METHOD_LINEAR_REGRESSION = 'linear_regression';
    const METHOD_MOVING_AVERAGE = 'moving_average';
    const METHOD_ML_LIGHT = 'ml_light';

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
