<template>
  <div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Stock Movements</h1>

    <div class="mb-6">
      <button @click="openCreateModal" class="bg-blue-500 text-white px-4 py-2 rounded">Record Movement</button>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-100">
          <tr>
            <th class="text-left px-4 py-2">Product</th>
            <th class="text-left px-4 py-2">Type</th>
            <th class="text-left px-4 py-2">Quantity</th>
            <th class="text-left px-4 py-2">Date</th>
            <th class="text-left px-4 py-2">User</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="movement in movements" :key="movement.id" class="border-b hover:bg-gray-50">
            <td class="px-4 py-2">{{ movement.product.name }}</td>
            <td class="px-4 py-2">
              <span :class="{
                'bg-green-100 text-green-800 px-2 py-1 rounded': movement.type === 'entry',
                'bg-red-100 text-red-800 px-2 py-1 rounded': movement.type === 'exit'
              }">
                {{ movement.type }}
              </span>
            </td>
            <td class="px-4 py-2">{{ movement.quantity }}</td>
            <td class="px-4 py-2">{{ new Date(movement.date).toLocaleDateString() }}</td>
            <td class="px-4 py-2">{{ movement.user.name }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import axios from 'axios'

export default {
  name: 'Movements',
  setup() {
    const movements = ref([])

    onMounted(async () => {
      try {
        const response = await axios.get('/stock-movements')
        movements.value = response.data.data.data
      } catch (error) {
        console.error('Error loading movements:', error)
      }
    })

    const openCreateModal = () => {
      alert('Create movement modal coming soon')
    }

    return {
      movements,
      openCreateModal
    }
  }
}
</script>
