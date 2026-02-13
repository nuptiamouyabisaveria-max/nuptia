@extends('layouts.app')

@section('title', 'Predictions')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Stock Predictions</h2>
        <button @click="generatePredictions" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">🔄 Generate Predictions</button>
    </div>

    <!-- Method Selection -->
    <div class="mb-6 flex gap-4">
        <select v-model="predictionMethod" class="px-4 py-2 border rounded">
            <option value="">Select Prediction Method</option>
            <option value="linear_regression">Linear Regression</option>
            <option value="moving_average">Moving Average</option>
            <option value="ml_light">ML Light</option>
        </select>
        <input v-model="predictionSearch" type="text" placeholder="Search products..." class="flex-1 px-4 py-2 border rounded">
    </div>

    <!-- Predictions Table -->
    <table class="w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="text-left px-4 py-2">Product</th>
                <th class="text-left px-4 py-2">Current Stock</th>
                <th class="text-left px-4 py-2">Predicted (30 days)</th>
                <th class="text-left px-4 py-2">Method</th>
                <th class="text-left px-4 py-2">Confidence</th>
                <th class="text-left px-4 py-2">Status</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="prediction in filteredPredictions" :key="prediction.id" class="border-b hover:bg-gray-50">
                <td class="px-4 py-2 font-semibold">@{{ prediction.product.name }}</td>
                <td class="px-4 py-2">@{{ prediction.product.current_quantity }}</td>
                <td class="px-4 py-2">
                    <span :class="{
                        'text-red-600 font-bold': prediction.predicted_quantity <= prediction.product.min_stock,
                        'text-orange-600 font-bold': prediction.predicted_quantity > prediction.product.min_stock && prediction.predicted_quantity <= prediction.product.optimal_stock,
                        'text-green-600': prediction.predicted_quantity > prediction.product.optimal_stock
                    }">
                        @{{ Math.round(prediction.predicted_quantity) }}
                    </span>
                </td>
                <td class="px-4 py-2">@{{ prediction.method }}</td>
                <td class="px-4 py-2">
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm">
                        @{{ Math.round(prediction.confidence * 100) }}%
                    </span>
                </td>
                <td class="px-4 py-2">
                    <span v-if="prediction.predicted_quantity <= prediction.product.min_stock" class="bg-red-100 text-red-800 px-3 py-1 rounded text-sm">
                        ⚠️ Will Run Out
                    </span>
                    <span v-else-if="prediction.predicted_quantity <= prediction.product.optimal_stock" class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded text-sm">
                        📉 Below Optimal
                    </span>
                    <span v-else class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm">
                        ✅ Healthy
                    </span>
                </td>
            </tr>
        </tbody>
    </table>

    <div v-if="filteredPredictions.length === 0" class="text-center py-8 text-gray-500">
        No predictions found. Click "Generate Predictions" to create them.
    </div>

    <!-- Chart Section (if using Chart.js) -->
    <div v-if="filteredPredictions.length > 0" class="mt-8 p-6 bg-gray-50 rounded-lg">
        <h3 class="text-xl font-bold mb-4">Prediction Insights</h3>
        <p class="text-gray-600">
            @{{ filteredPredictions.filter(p => p.predicted_quantity <= p.product.min_stock).length }} products will likely run out of stock within 30 days.
        </p>
    </div>
</div>
@endsection
