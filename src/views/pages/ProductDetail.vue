<template>
    <div class="min-h-screen bg-surface-50 dark:bg-surface-900 py-10 px-6 lg:px-20">
        <div class="max-w-5xl mx-auto">
            <div class="mb-6">
                <RouterLink to="/" class="text-primary-600 hover:underline">← Back to home</RouterLink>
            </div>

            <div v-if="loading" class="flex justify-center items-center py-20">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
            </div>

            <div v-else-if="error" class="text-center py-20">
                <div class="text-red-500 text-lg">Failed to load product.</div>
            </div>

            <div v-else class="bg-white dark:bg-surface-800 rounded-xl shadow-lg overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                    <div class="p-10 flex items-center justify-center bg-surface-100 dark:bg-surface-700">
                        <img :src="product.image_url || product.image_path" :alt="product.name" class="max-h-72 object-contain"
                             @error="handleImageError" />
                    </div>
                    <div class="p-8">
                        <h1 class="text-2xl font-bold text-surface-900 dark:text-surface-0 mb-2">{{ product.name }}</h1>
                        <div class="text-sm text-surface-600 dark:text-surface-300 mb-4">{{ product.category }}</div>
                        <p class="text-surface-800 dark:text-surface-200 leading-relaxed whitespace-pre-line">{{ product.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ApiService } from '@/service/ApiService.js';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const product = ref({});
const loading = ref(true);
const error = ref(false);

const loadProduct = async () => {
    try {
        loading.value = true;
        error.value = false;
        const id = route.params.id;
        const response = await ApiService.getProduct(id);
        product.value = response.data || response || {};
    } catch (e) {
        console.error('Failed to load product:', e);
        error.value = true;
    } finally {
        loading.value = false;
    }
};

const handleImageError = (event) => {
    const img = event.target;
    if (img.__fallbackApplied) return;
    img.__fallbackApplied = true;
    img.src = '/images/placeholder-product.png';
};

onMounted(loadProduct);
</script>

<style scoped>
.grid { display: grid; }
.grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
@media (min-width: 768px) {
  .md\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
</style>


