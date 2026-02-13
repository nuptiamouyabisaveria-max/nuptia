@extends('layouts.app')

@section('title', 'Inventory Management')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Inventory Management</h2>
        <button @click="showStartInventory = true" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">+ Start Inventory Count</button>
    </div>

    <!-- Start Inventory Form -->
    <div v-if="showStartInventory" class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
        <h3 class="text-xl font-bold mb-4">Start Physical Inventory Count</h3>
        <form @submit.prevent="startInventory">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Inventory Name</label>
                    <input v-model="newInventory.name" type="text" placeholder="e.g., Monthly Count Jan 2026" class="w-full px-4 py-2 border rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Notes</label>
                    <input v-model="newInventory.notes" type="text" class="w-full px-4 py-2 border rounded">
                </div>
            </div>
            <div class="mt-4 flex gap-2">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Start Count</button>
                <button type="button" @click="showStartInventory = false" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
            </div>
        </form>
    </div>

    <!-- Active Inventory Section -->
    <div v-if="activeInventory" class="mb-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
        <h3 class="text-xl font-bold mb-4">Current Inventory: @{{ activeInventory.name }}</h3>
        
        <div class="mb-4">
            <p class="text-gray-700">
                Started: @{{ new Date(activeInventory.created_at).toLocaleString() }} | 
                Status: <span class="font-bold text-yellow-600">In Progress</span>
            </p>
        </div>

        <!-- Add Item Form -->
        <form @submit.prevent="addInventoryItem" class="mb-4 p-4 bg-white rounded border">
            <h4 class="font-bold mb-3">Record Counted Item</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Product</label>
                    <select v-model="inventoryItem.product_id" class="w-full px-4 py-2 border rounded" required>
                        <option value="">Select Product</option>
                        <option v-for="product in products" :key="product.id" :value="product.id">@{{ product.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Counted Quantity</label>
                    <input v-model.number="inventoryItem.counted_quantity" type="number" class="w-full px-4 py-2 border rounded" required>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Item</button>
                </div>
            </div>
        </form>

        <!-- Counted Items -->
        <div class="mb-4">
            <h4 class="font-bold mb-3">Counted Items (@{{ inventoryDetails.length }})</h4>
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left px-3 py-2">Product</th>
                        <th class="text-left px-3 py-2">System Qty</th>
                        <th class="text-left px-3 py-2">Counted Qty</th>
                        <th class="text-left px-3 py-2">Difference</th>
                        <th class="text-left px-3 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in inventoryDetails" :key="item.id" class="border-b">
                        <td class="px-3 py-2">@{{ item.product.name }}</td>
                        <td class="px-3 py-2">@{{ item.product.current_quantity }}</td>
                        <td class="px-3 py-2 font-bold">@{{ item.counted_quantity }}</td>
                        <td class="px-3 py-2" :class="{ 'text-red-600 font-bold': item.counted_quantity !== item.product.current_quantity }">
                            @{{ item.counted_quantity - item.product.current_quantity }}
                        </td>
                        <td class="px-3 py-2">
                            <button @click="removeInventoryItem(item.id)" class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600">Remove</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex gap-2">
            <button @click="completeInventory" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Complete Inventory</button>
            <button @click="cancelInventory" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Cancel Count</button>
        </div>
    </div>

    <!-- Past Inventories -->
    <div>
        <h3 class="text-xl font-bold mb-4">Past Inventories</h3>
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left px-4 py-2">Name</th>
                    <th class="text-left px-4 py-2">Date</th>
                    <th class="text-left px-4 py-2">Items Counted</th>
                    <th class="text-left px-4 py-2">Discrepancies</th>
                    <th class="text-left px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="inv in pastInventories" :key="inv.id" class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2 font-semibold">@{{ inv.name }}</td>
                    <td class="px-4 py-2">@{{ new Date(inv.created_at).toLocaleDateString() }}</td>
                    <td class="px-4 py-2">@{{ inv.inventory_details.length }}</td>
                    <td class="px-4 py-2">
                        <span class="text-red-600 font-bold">
                            @{{ inv.inventory_details.filter(d => d.counted_quantity !== d.product.current_quantity).length }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        <button @click="viewInventory(inv)" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">View</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div v-if="pastInventories.length === 0" class="text-center py-8 text-gray-500">
            No past inventories.
        </div>
    </div>
</div>
@endsection
