<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->enum('method', ['linear_regression', 'moving_average', 'ml_light']);
            $table->integer('period_days');
            $table->integer('predicted_quantity');
            $table->float('rupture_risk')->default(0);
            $table->text('recommendation')->nullable();
            $table->float('confidence_score')->default(0);
            $table->dateTime('prediction_date');
            $table->json('forecast_data')->nullable();
            $table->timestamps();
            
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->index('product_id');
            $table->index('prediction_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
