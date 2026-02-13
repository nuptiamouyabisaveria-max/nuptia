<template>
  <div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Total Products</h3>
        <p class="text-3xl font-bold mt-2">{{ stats.total_products }}</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Stock Value</h3>
        <p class="text-3xl font-bold mt-2">${{ stats.total_stock_value?.toFixed(2) }}</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Low Stock Items</h3>
        <p class="text-3xl font-bold mt-2 text-red-600">{{ stats.low_stock_products }}</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium">Pending Alerts</h3>
        <p class="text-3xl font-bold mt-2 text-yellow-600">{{ stats.pending_alerts }}</p>
      </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow mb-8">
      <h2 class="text-xl font-bold mb-4">Recent Movements</h2>
      <table class="w-full">
        <thead>
          <tr class="border-b">
            <th class="text-left py-2">Product</th>
            <th class="text-left py-2">Type</th>
            <th class="text-left py-2">Quantity</th>
            <th class="text-left py-2">Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="movement in recentMovements" :key="movement.id" class="border-b hover:bg-gray-50">
            <td class="py-2">{{ movement.product.name }}</td>
            <td class="py-2">
              <span :class="{
                'bg-green-100 text-green-800 px-2 py-1 rounded': movement.type === 'entry',
                'bg-red-100 text-red-800 px-2 py-1 rounded': movement.type === 'exit'
              }">
                {{ movement.type }}
              </span>
            </td>
            <td class="py-2">{{ movement.quantity }}</td>
            <td class="py-2">{{ new Date(movement.date).toLocaleDateString() }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="flex gap-4">
      <button @click="exportExcel" class="bg-green-500 text-white px-4 py-2 rounded">Export to Excel</button>
      <button @click="exportPdf" class="bg-blue-500 text-white px-4 py-2 rounded">Export to PDF</button>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import axios from 'axios'

export default {
  name: 'Dashboard',
  setup() {
    const stats = ref({})
    const recentMovements = ref([])

    onMounted(async () => {
      try {
        const statsRes = await axios.get('/dashboard')
        stats.value = statsRes.data.data

        const movementsRes = await axios.get('/dashboard/recent-movements?limit=5')
        recentMovements.value = movementsRes.data.data
      } catch (error) {
        console.error('Error loading dashboard:', error)
      }
    })

    const exportExcel = async () => {
      try {
        const response = await axios.get('/exports/dashboard', { responseType: 'blob' })
        const url = window.URL.createObjectURL(response.data)
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', 'dashboard.csv')
        document.body.appendChild(link)
        link.click()
      } catch (error) {
        console.error('Export error:', error)
      }
    }

    const exportPdf = () => {
      alert('PDF export functionality coming soon')
    }

    return {
      stats,
      recentMovements,
      exportExcel,
      exportPdf
    }
  }
}
</script>
