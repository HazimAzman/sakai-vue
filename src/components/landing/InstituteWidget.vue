<template>
    <div id="institute" class="py-6 px-6 lg:px-20 mx-0 my-12 lg:mx-20">
        <div class="text-center mb-16">
            <div class="text-surface-900 dark:text-surface-0 font-bold mb-4 text-5xl">Institute</div>
            <div class="h-1 w-16 bg-primary-500 mx-auto mb-4"></div>
            <span class="text-surface-600 dark:text-surface-300 text-2xl">People behind your great experience.</span>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="text-center py-12">
            <div class="text-red-500 text-lg">Failed to load institutes. Please try again later.</div>
        </div>

        <!-- Institute Logos Grid -->
        <div v-else class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6">
            <div 
                v-for="institute in institutes" 
                :key="institute.id"
                class="bg-white dark:bg-surface-800 rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow duration-300"
            >
                <img 
                    :src="institute.image_path" 
                    :alt="institute.name" 
                    class="h-16 mx-auto mb-4 object-contain"
                    @error="handleImageError"
                />
                <div class="text-sm font-semibold text-surface-900 dark:text-surface-0">{{ institute.name }}</div>
                <div class="text-xs text-surface-600 dark:text-surface-300">({{ institute.abbreviation }})</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ApiService } from '@/service/ApiService.js';
import { onMounted, ref } from 'vue';

const institutes = ref([]);
const loading = ref(true);
const error = ref(false);

const loadInstitutes = async () => {
    try {
        loading.value = true;
        error.value = false;
        
        const response = await ApiService.getInstitutes();
        institutes.value = response.data || response;
        
        // If no institutes, show empty state
        if (!institutes.value || institutes.value.length === 0) {
            institutes.value = [];
        }
    } catch (err) {
        console.error('Failed to load institutes:', err);
        error.value = true;
        institutes.value = [];
    } finally {
        loading.value = false;
    }
};

const handleImageError = (event) => {
    const img = event.target;
    if (img.__fallbackApplied) return;
    img.__fallbackApplied = true;
    img.src = '/images/placeholder-institute.png';
};

onMounted(() => {
    loadInstitutes();
});
</script>
