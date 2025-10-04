<template>
    <div id="service" class="py-6 px-6 lg:px-20 mt-8 mx-0 lg:mx-20">
        <div class="grid grid-cols-12 gap-4 justify-center">
            <div class="col-span-12 text-center mt-20 mb-12">
                <div class="text-surface-900 dark:text-surface-0 font-bold mb-4 text-5xl">What we can provide</div>
                <div class="h-1 w-16 bg-primary-500 mx-auto mb-4"></div>
                <span class="text-surface-600 dark:text-surface-300 text-2xl">Whatever we do, we do with you in mind.</span>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="col-span-12 flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="col-span-12 text-center py-12">
                <div class="text-red-500 text-lg">Failed to load services. Please try again later.</div>
            </div>

            <!-- Service Cards Grid -->
            <div v-else class="col-span-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div 
                    v-for="service in services" 
                    :key="service.id"
                    class="bg-white dark:bg-surface-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300"
                >
                    <img 
                        :src="service.image_path" 
                        :alt="service.name" 
                        class="w-full h-48 object-cover"
                        @error="handleImageError"
                    />
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-surface-900 dark:text-surface-0 mb-2">{{ service.name }}</h3>
                        <p class="text-surface-600 dark:text-surface-300">{{ service.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ApiService } from '@/service/ApiService.js';
import { onMounted, ref } from 'vue';

const services = ref([]);
const loading = ref(true);
const error = ref(false);

const loadServices = async () => {
    try {
        loading.value = true;
        error.value = false;
        
        const response = await ApiService.getServices();
        services.value = response.data || response;
        
        // If no services, show empty state
        if (!services.value || services.value.length === 0) {
            services.value = [];
        }
    } catch (err) {
        console.error('Failed to load services:', err);
        error.value = true;
        services.value = [];
    } finally {
        loading.value = false;
    }
};

const handleImageError = (event) => {
    // Set a placeholder image if the original fails to load
    event.target.src = '/images/placeholder-service.png';
};

onMounted(() => {
    loadServices();
});
</script>
