@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Products</h2>
        <button @click="showAddProduct = true" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">+ Add Product</button>
    </div>

    <!-- Add Product Form -->
    <div v-if="showAddProduct" class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
        <h3 class="text-xl font-bold mb-4">Add New Product</h3>
        <form @submit.prevent="addProduct">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Product Name</label>
                    <input v-model="newProduct.name" type="text" class="w-full px-4 py-2 border rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Category</label>
                    <select v-model="newProduct.category_id" class="w-full px-4 py-2 border rounded" required>
                        <option value="">Select Category</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">@{{ cat.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Current Quantity</label>
                    <input v-model.number="newProduct.current_quantity" type="number" class="w-full px-4 py-2 border rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Minimum Stock</label>
                    <input v-model.number="newProduct.min_stock" type="number" class="w-full px-4 py-2 border rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Optimal Stock</label>
                    <input v-model.number="newProduct.optimal_stock" type="number" class="w-full px-4 py-2 border rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Price</label>
                    <input v-model.number="newProduct.price" type="number" step="0.01" class="w-full px-4 py-2 border rounded" required>
                </div>
            </div>
            <div class="mt-4 flex gap-2">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Save Product</button>
                <button type="button" @click="showAddProduct = false" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
            </div>
        </form>
    </div>

    <!-- Filter & Search -->
    <div class="mb-6 flex gap-4">
        <input v-model="productSearch" type="text" placeholder="Search products..." class="flex-1 px-4 py-2 border rounded">
        <select v-model="categoryFilter" class="px-4 py-2 border rounded">
            <option value="">All Categories</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">@{{ cat.name }}</option>
        </select>
    </div>

    <!-- Products Table -->
    <table class="w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="text-left px-4 py-2">Name</th>
                <th class="text-left px-4 py-2">Category</th>
                <th class="text-left px-4 py-2">Stock</th>
                <th class="text-left px-4 py-2">Price</th>
                <th class="text-left px-4 py-2">Status</th>
                <th class="text-left px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="product in filteredProducts" :key="product.id" class="border-b hover:bg-gray-50">
                <td class="px-4 py-2 font-semibold">@{{ product.name }}</td>
                <td class="px-4 py-2">@{{ product.category.name }}</td>
                <td class="px-4 py-2">
                    <span :class="{ 'text-red-600 font-bold': product.current_quantity <= product.min_stock }">
                        @{{ product.current_quantity }} / @{{ product.optimal_stock }}
                    </span>
                </td>
                <td class="px-4 py-2">${{ product.price }}</td>
                <td class="px-4 py-2">
                    <span v-if="product.current_quantity <= product.min_stock" class="bg-red-100 text-red-800 px-3 py-1 rounded text-sm">
                        Low Stock
                    </span>
                    <span v-else class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm">
                        Normal
                    </span>
                </td>
                <td class="px-4 py-2 space-x-2">
                    <button @click="editProduct(product)" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">Edit</button>
                    <button @click="deleteProduct(product.id)" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>

    <div v-if="filteredProducts.length === 0" class="text-center py-8 text-gray-500">
        No products found.
    </div>
</div>
@endsection
