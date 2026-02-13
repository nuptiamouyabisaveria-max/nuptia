<template>
  <div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Alerts</h1>

    <div class="mb-6">
      <button @click="markAllRead" class="bg-gray-500 text-white px-4 py-2 rounded">Mark All as Read</button>
    </div>

    <div class="space-y-4">
      <div v-for="alert in alerts" :key="alert.id" 
           :class="{
             'bg-red-50 border-l-4 border-red-500': alert.severity === 'critical',
             'bg-orange-50 border-l-4 border-orange-500': alert.severity === 'high',
             'bg-yellow-50 border-l-4 border-yellow-500': alert.severity === 'medium',
             'bg-blue-50 border-l-4 border-blue-500': alert.severity === 'low'
           }"
           class="p-4 rounded">
        <div class="flex justify-between items-start">
          <div class="flex-1">
            <h3 class="font-bold">{{ alert.product.name }}</h3>
            <p class="text-gray-700 mt-1">{{ alert.message }}</p>
            <p class="text-sm text-gray-500 mt-2">{{ new Date(alert.created_at).toLocaleString() }}</p>
          </div>
          <button @click="markAsRead(alert)" class="text-blue-600 hover:underline">Read</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import axios from 'axios'

export default {
  name: 'Alerts',
  setup() {
    const alerts = ref([])

    onMounted(loadAlerts)

    async function loadAlerts() {
      try {
        const response = await axios.get('/alerts')
        alerts.value = response.data.data.data
      } catch (error) {
        console.error('Error loading alerts:', error)
      }
    }

    async function markAsRead(alert) {
      try {
        await axios.post(`/alerts/${alert.id}/mark-as-read`)
        loadAlerts()
      } catch (error) {
        console.error('Error marking alert as read:', error)
      }
    }

    async function markAllRead() {
      try {
        await axios.post('/alerts/mark-all-as-read')
        loadAlerts()
      } catch (error) {
        console.error('Error marking all as read:', error)
      }
    }

    return {
      alerts,
      markAsRead,
      markAllRead
    }
  }
}
</script>
