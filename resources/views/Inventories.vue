<template>
  <div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Inventories</h1>

    <div class="mb-6">
      <button @click="openCreateModal" class="bg-blue-500 text-white px-4 py-2 rounded">Create Inventory</button>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-100">
          <tr>
            <th class="text-left px-4 py-2">ID</th>
            <th class="text-left px-4 py-2">Date</th>
            <th class="text-left px-4 py-2">Status</th>
            <th class="text-left px-4 py-2">User</th>
            <th class="text-left px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="inventory in inventories" :key="inventory.id" class="border-b hover:bg-gray-50">
            <td class="px-4 py-2">#{{ inventory.id }}</td>
            <td class="px-4 py-2">{{ new Date(inventory.date).toLocaleDateString() }}</td>
            <td class="px-4 py-2">
              <span :class="{
                'bg-yellow-100 text-yellow-800 px-2 py-1 rounded': inventory.status === 'pending',
                'bg-blue-100 text-blue-800 px-2 py-1 rounded': inventory.status === 'in_progress',
                'bg-green-100 text-green-800 px-2 py-1 rounded': inventory.status === 'completed'
              }">
                {{ inventory.status }}
              </span>
            </td>
            <td class="px-4 py-2">{{ inventory.user.name }}</td>
            <td class="px-4 py-2">
              <a href="#" class="text-blue-600 hover:underline">View</a>
            </td>
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
  name: 'Inventories',
  setup() {
    const inventories = ref([])

    onMounted(async () => {
      try {
        const response = await axios.get('/inventories')
        inventories.value = response.data.data.data
      } catch (error) {
        console.error('Error loading inventories:', error)
      }
    })

    const openCreateModal = () => {
      alert('Create inventory modal coming soon')
    }

    return {
      inventories,
      openCreateModal
    }
  }
}
</script>
