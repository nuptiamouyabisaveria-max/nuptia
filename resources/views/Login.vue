<template>
  <transition name="fade" mode="out-in">
    <div class="max-w-2xl mx-auto">
      <h1 class="text-3xl font-bold mb-8">Login</h1>

    <div class="bg-white p-8 rounded-lg shadow">
      <form @submit.prevent="login">
        <div class="mb-6">
          <label class="block text-gray-700 font-bold mb-2">Email</label>
          <input 
            v-model="email" 
            type="email" 
            class="w-full px-4 py-2 border rounded"
            required
          >
        </div>
        <div class="mb-6">
          <label class="block text-gray-700 font-bold mb-2">Password</label>
          <input 
            v-model="password" 
            type="password" 
            class="w-full px-4 py-2 border rounded"
            required
          >
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded font-bold">Login</button>
      </form>

      <p class="text-center mt-4">
        Don't have an account? 
        <router-link to="/register" class="text-blue-600 hover:underline">Register</router-link>
      </p>
    </div>
  </div>
  </transition>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

export default {
  name: 'Login',
  setup() {
    const router = useRouter()
    const email = ref('')
    const password = ref('')

    const login = async () => {
      try {
        const response = await axios.post('/auth/login', {
          email: email.value,
          password: password.value
        })
        localStorage.setItem('token', response.data.data.token)
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.data.token}`
        router.push('/dashboard')
      } catch (error) {
        alert('Login failed: ' + (error.response?.data?.message || 'Unknown error'))
      }
    }

    return {
      email,
      password,
      login
    }
  }
}
</script>
