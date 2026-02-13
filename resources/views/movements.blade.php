@extends('layouts.app')

@section('title', 'Stock Movements')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Stock Movements</h2>
        <button @click="showAddMovement = true" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">+ Record Movement</button>
    </div>

    <!-- Add Movement Form -->
    <div v-if="showAddMovement" class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
        <h3 class="text-xl font-bold mb-4">Record Stock Movement</h3>
        <form @submit.prevent="recordMovement">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Product</label>
                    <select v-model="newMovement.product_id" class="w-full px-4 py-2 border rounded" required>
                        <option value="">Select Product</option>
                        <option v-for="product in products" :key="product.id" :value="product.id">@{{ product.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Type</label>
                    <select v-model="newMovement.type" class="w-full px-4 py-2 border rounded" required>
                        <option value="">Select Type</option>
                        <option value="entry">Entry (Stock In)</option>
                        <option value="exit">Exit (Stock Out)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Quantity</label>
                    <input v-model.number="newMovement.quantity" type="number" class="w-full px-4 py-2 border rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Reference</label>
                    <input v-model="newMovement.reference" type="text" placeholder="Invoice, PO, etc." class="w-full px-4 py-2 border rounded">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2">Notes</label>
                    <textarea v-model="newMovement.notes" class="w-full px-4 py-2 border rounded" rows="3"></textarea>
                </div>
            </div>
            <div class="mt-4 flex gap-2">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Record Movement</button>
                <button type="button" @click="showAddMovement = false" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
            </div>
        </form>
    </div>

    <!-- Filter -->
    <div class="mb-6 flex gap-4">
        <select v-model="typeFilter" class="px-4 py-2 border rounded">
            <option value="">All Types</option>
            <option value="entry">Entry</option>
            <option value="exit">Exit</option>
        </select>
        <input v-model="movementSearch" type="text" placeholder="Search by product..." class="flex-1 px-4 py-2 border rounded">
    </div>

    <!-- Movements Table -->
    <table class="w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="text-left px-4 py-2">Date</th>
                <th class="text-left px-4 py-2">Product</th>
                <th class="text-left px-4 py-2">Type</th>
                <th class="text-left px-4 py-2">Quantity</th>
                <th class="text-left px-4 py-2">Reference</th>
                <th class="text-left px-4 py-2">Notes</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="movement in filteredMovements" :key="movement.id" class="border-b hover:bg-gray-50">
                <td class="px-4 py-2">@{{ new Date(movement.created_at).toLocaleDateString() }}</td>
                <td class="px-4 py-2 font-semibold">@{{ movement.product.name }}</td>
                <td class="px-4 py-2">
                    <span :class="movement.type === 'entry' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-3 py-1 rounded text-sm">
                        @{{ movement.type === 'entry' ? '📥 Entry' : '📤 Exit' }}
                    </span>
                </td>
                <td class="px-4 py-2">@{{ movement.quantity }}</td>
                <td class="px-4 py-2">@{{ movement.reference || '-' }}</td>
                <td class="px-4 py-2 text-sm text-gray-600">@{{ movement.notes || '-' }}</td>
            </tr>
        </tbody>
    </table>

    <div v-if="filteredMovements.length === 0" class="text-center py-8 text-gray-500">
        No movements found.
    </div>
</div>
@endsection
