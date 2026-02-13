<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\StockMovementsController;
use App\Http\Controllers\Api\InventoriesController;
use App\Http\Controllers\Api\AlertsController;
use App\Http\Controllers\Api\PredictionsController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ExportsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('v1')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        // Categories
        Route::apiResource('categories', CategoriesController::class);

        // Products
        Route::apiResource('products', ProductsController::class);

        // Stock Movements
        Route::apiResource('stock-movements', StockMovementsController::class)->only(['index', 'store', 'show']);

        // Inventories
        Route::prefix('inventories')->group(function () {
            Route::get('/', [InventoriesController::class, 'index']);
            Route::post('/', [InventoriesController::class, 'store']);
            Route::get('/{inventory}', [InventoriesController::class, 'show']);
            Route::post('/{inventory}/add-items', [InventoriesController::class, 'addItems']);
            Route::post('/{inventory}/complete', [InventoriesController::class, 'complete']);
            Route::post('/{inventory}/archive', [InventoriesController::class, 'archive']);
        });

        // Alerts
        Route::prefix('alerts')->group(function () {
            Route::get('/', [AlertsController::class, 'index']);
            Route::get('/unread-count', [AlertsController::class, 'unreadCount']);
            Route::post('/{alert}/mark-as-read', [AlertsController::class, 'markAsRead']);
            Route::post('/mark-all-as-read', [AlertsController::class, 'markAllAsRead']);
            Route::delete('/{alert}', [AlertsController::class, 'destroy']);
        });

        // Predictions
        Route::prefix('predictions')->group(function () {
            Route::get('/', [PredictionsController::class, 'index']);
            Route::get('/{prediction}', [PredictionsController::class, 'show']);
            Route::post('/products/{product}/generate', [PredictionsController::class, 'generate']);
        });

        // Dashboard
        Route::prefix('dashboard')->group(function () {
            Route::get('/', [DashboardController::class, 'index']);
            Route::get('/low-stock-products', [DashboardController::class, 'lowStockProducts']);
            Route::get('/recent-movements', [DashboardController::class, 'recentMovements']);
            Route::get('/stock-by-category', [DashboardController::class, 'stockByCategory']);
            Route::get('/movements-trend', [DashboardController::class, 'movementsTrend']);
            Route::get('/rotation-rate', [DashboardController::class, 'rotationRate']);
        });

        // Exports
        Route::prefix('exports')->group(function () {
            Route::get('/products/pdf', [ExportsController::class, 'exportProductsPdf']);
            Route::get('/products/excel', [ExportsController::class, 'exportProductsExcel']);
            Route::get('/movements/pdf', [ExportsController::class, 'exportMovementsPdf']);
            Route::get('/movements/excel', [ExportsController::class, 'exportMovementsExcel']);
            Route::get('/inventory/{inventory}/pdf', [ExportsController::class, 'exportInventoryPdf']);
            Route::get('/product/{product}/fiche', [ExportsController::class, 'generateProductFiche']);
            Route::get('/dashboard', [ExportsController::class, 'exportDashboardData']);
        });
    });
});
