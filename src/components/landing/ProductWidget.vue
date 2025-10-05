<template>
    <div id="product" class="py-6 px-6 lg:px-20 mx-0 my-12 lg:mx-20">
        <div class="text-center mb-16">
            <div class="text-surface-900 dark:text-surface-0 font-bold mb-4 text-5xl">Product</div>
            <div class="h-1 w-16 bg-primary-500 mx-auto mb-4"></div>
            <span class="text-surface-600 dark:text-surface-300 text-2xl">People behind your great experience.</span>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="text-center py-12">
            <div class="text-red-500 text-lg">Failed to load products. Please try again later.</div>
        </div>

        <!-- Product Brand Logos Grid -->
        <div v-else class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4">
            <div 
                v-for="product in products" 
                :key="product.id"
                class="bg-white dark:bg-surface-800 rounded-lg shadow-md p-4 text-center hover:shadow-lg transition-shadow duration-300"
            >
                <img 
                    :src="product.image_path" 
                    :alt="product.name" 
                    class="h-16 mx-auto mb-4 object-contain"
                    @error="handleImageError"
                />
                <div class="text-sm font-semibold text-surface-900 dark:text-surface-0">{{ product.name }}</div>
                <div class="text-xs text-surface-600 dark:text-surface-400 mt-1">{{ product.category }}</div>
            </div>
        </div>


    </div>
</template>

<style scoped>
/* Grid styling */
.grid {
    display: grid;
}

.grid-cols-2 {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

@media (min-width: 768px) {
    .md\:grid-cols-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}

@media (min-width: 1024px) {
    .lg\:grid-cols-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}
</style>

<script setup>
import { ApiService } from '@/service/ApiService.js';
import { onMounted, ref } from 'vue';

const products = ref([]);
const loading = ref(true);
const error = ref(false);


const loadProducts = async () => {
    try {
        loading.value = true;
        error.value = false;
        
        const response = await ApiService.getProducts();
        products.value = response.data || response;
        
        // If no products, show empty state
        if (!products.value || products.value.length === 0) {
            products.value = [];
        }
    } catch (err) {
        console.error('Failed to load products:', err);
        error.value = true;
        products.value = [];
    } finally {
        loading.value = false;
    }
};

const handleImageError = (event) => {
    // Set a placeholder image if the original fails to load
    event.target.src = '/images/placeholder-product.png';
};

onMounted(() => {
    loadProducts();
});
</script>