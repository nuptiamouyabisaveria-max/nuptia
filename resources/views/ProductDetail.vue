<template>
  <div class="max-w-4xl mx-auto">
    <router-link to="/products" class="text-blue-600 hover:underline mb-4 inline-block">← Back to Products</router-link>
    
    <div class="bg-white p-8 rounded-lg shadow" v-if="product">
      <h1 class="text-3xl font-bold mb-4">{{ product.name }}</h1>
      
      <div class="grid grid-cols-2 gap-8">
        <div>
          <p class="text-gray-600 mb-4"><strong>Barcode:</strong> {{ product.barcode }}</p>
          <p class="text-gray-600 mb-4"><strong>Supplier:</strong> {{ product.supplier }}</p>
          <p class="text-gray-600 mb-4"><strong>Category:</strong> {{ product.category.name }}</p>
          <p class="text-gray-600 mb-4"><strong>Price:</strong> ${{ product.price }}</p>
        </div>
        <div>
          <p class="text-gray-600 mb-4"><strong>Current Stock:</strong> {{ product.current_quantity }}</p>
          <p class="text-gray-600 mb-4"><strong>Min Stock:</strong> {{ product.min_stock }}</p>
          <p class="text-gray-600 mb-4"><strong>Optimal Stock:</strong> {{ product.optimal_stock }}</p>
          <p class="text-gray-600 mb-4"><strong>Description:</strong> {{ product.description }}</p>
        </div>
      </div>

      <div class="mt-8">
        <button @click="generatePrediction" class="bg-green-500 text-white px-4 py-2 rounded">Generate Prediction</button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

export default {
  name: 'ProductDetail',
  setup() {
    const route = useRoute()
    const product = ref(null)

    onMounted(async () => {
      try {
        const response = await axios.get(`/products/${route.params.id}`)
        product.value = response.data.data
      } catch (error) {
        console.error('Error loading product:', error)
      }
    })

    const generatePrediction = async () => {
      try {
        const response = await axios.post(`/predictions/products/${product.value.id}/generate`, {
          period_days: 7
        })
        alert('Prediction generated successfully!')
      } catch (error) {
        alert('Error generating prediction')
      }
    }

    return {
      product,
      generatePrediction
    }
  }
}
</script>
