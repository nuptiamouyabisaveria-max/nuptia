@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Total Products</h3>
        <p class="text-3xl font-bold mt-2">@{{ stats.total_products || 0 }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Stock Value</h3>
        <p class="text-3xl font-bold mt-2">$@{{ (stats.total_stock_value || 0).toFixed(2) }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Low Stock Items</h3>
        <p class="text-3xl font-bold mt-2 text-red-600">@{{ stats.low_stock_products || 0 }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Pending Alerts</h3>
        <p class="text-3xl font-bold mt-2 text-yellow-600">@{{ stats.pending_alerts || 0 }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4">Recent Stock Movements</h2>
        <div class="space-y-2">
            <div v-for="movement in recentMovements" :key="movement.id" class="border-b pb-2">
                <p class="font-semibold">@{{ movement.product.name }}</p>
                <p class="text-gray-600 text-sm">@{{ movement.type }} - Qty: @{{ movement.quantity }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
        <div class="space-y-2">
            <button @click="goTo('products')" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">📦 View Products</button>
            <button @click="goTo('movements')" class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">📤 Stock Movements</button>
            <button @click="goTo('inventory')" class="w-full bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">📊 Inventory Count</button>
            <button @click="goTo('alerts')" class="w-full bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">⚠️ View Alerts</button>
        </div>
    </div>
</div>
@endsection
