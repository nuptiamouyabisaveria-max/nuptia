<template>
  <div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Products</h1>

    <div class="mb-6 flex gap-4">
      <input 
        v-model="search" 
        type="text" 
        placeholder="Search products..." 
        class="flex-1 px-4 py-2 border rounded"
      >
      <button @click="openCreateModal" class="bg-blue-500 text-white px-4 py-2 rounded">Add Product</button>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-100">
          <tr>
            <th class="text-left px-4 py-2">Name</th>
            <th class="text-left px-4 py-2">Category</th>
            <th class="text-left px-4 py-2">Stock</th>
            <th class="text-left px-4 py-2">Price</th>
            <th class="text-left px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in products" :key="product.id" class="border-b hover:bg-gray-50">
            <td class="px-4 py-2">{{ product.name }}</td>
            <td class="px-4 py-2">{{ product.category.name }}</td>
            <td class="px-4 py-2">
              <span :class="{
                'text-red-600 font-bold': product.current_quantity <= product.min_stock
              }">
                {{ product.current_quantity }} / {{ product.optimal_stock }}
              </span>
            </td>
            <td class="px-4 py-2">${{ product.price }}</td>
            <td class="px-4 py-2">
              <router-link :to="`/products/${product.id}`" class="text-blue-600 hover:underline">View</router-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-4 flex justify-center gap-2">
      <button v-for="i in totalPages" :key="i" @click="page = i" 
              :class="{'bg-blue-500 text-white': page === i, 'bg-gray-200': page !== i}" 
              class="px-3 py-1 rounded">
        {{ i }}
      </button>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

export default {
  name: 'Products',
  setup() {
    const products = ref([])
    const search = ref('')
    const page = ref(1)
    const total = ref(0)
    const perPage = ref(10)

    const totalPages = computed(() => Math.ceil(total.value / perPage.value))

    const loadProducts = async () => {
      try {
        const params = {
          page: page.value,
          per_page: perPage.value
        }
        if (search.value) {
          params.search = search.value
        }
        const response = await axios.get('/products', { params })
        products.value = response.data.data.data
        total.value = response.data.data.total
      } catch (error) {
        console.error('Error loading products:', error)
      }
    }

    onMounted(loadProducts)

    const openCreateModal = () => {
      alert('Create modal coming soon')
    }

    return {
      products,
      search,
      page,
      totalPages,
      loadProducts,
      openCreateModal
    }
  },
  watch: {
    search() {
      this.page = 1
      this.loadProducts()
    },
    page() {
      this.loadProducts()
    }
  }
}
</script>
