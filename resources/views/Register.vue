<template>
  <div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Register</h1>

    <div class="bg-white p-8 rounded-lg shadow">
      <form @submit.prevent="register">
        <div class="mb-6">
          <label class="block text-gray-700 font-bold mb-2">Name</label>
          <input 
            v-model="name" 
            type="text" 
            class="w-full px-4 py-2 border rounded"
            required
          >
        </div>
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
        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded font-bold">Register</button>
      </form>

      <p class="text-center mt-4">
        Already have an account? 
        <router-link to="/login" class="text-blue-600 hover:underline">Login</router-link>
      </p>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

export default {
  name: 'Register',
  setup() {
    const router = useRouter()
    const name = ref('')
    const email = ref('')
    const password = ref('')

    const register = async () => {
      try {
        await axios.post('/auth/register', {
          name: name.value,
          email: email.value,
          password: password.value
        })
        alert('Registration successful! Please login.')
        router.push('/login')
      } catch (error) {
        alert('Registration failed: ' + (error.response?.data?.message || 'Unknown error'))
      }
    }

    return {
      name,
      email,
      password,
      register
    }
  }
}
</script>
