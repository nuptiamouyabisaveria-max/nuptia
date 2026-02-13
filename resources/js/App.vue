<template>
  <div id="app" class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-lg">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="text-2xl font-bold text-blue-600">Stock Manager</div>
          <div class="space-x-4">
            <router-link to="/dashboard" class="text-gray-700 hover:text-blue-600">Dashboard</router-link>
            <router-link to="/products" class="text-gray-700 hover:text-blue-600">Products</router-link>
            <router-link to="/movements" class="text-gray-700 hover:text-blue-600">Movements</router-link>
            <button @click="logout" class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
          </div>
        </div>
      </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <router-view />
    </div>
  </div>
</template>

<script>
import { useRouter } from 'vue-router'

export default {
  name: 'App',
  setup() {
    const router = useRouter()

    const logout = () => {
      localStorage.removeItem('token')
      router.push('/login')
    }

    return { logout }
  }
}
</script>

<style scoped>
/* Global styles can go here */
</style>
