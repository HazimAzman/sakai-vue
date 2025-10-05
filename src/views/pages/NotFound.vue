

<template>
    <div class="flex items-center justify-center min-h-screen overflow-hidden bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center text-center">
            <!-- Aztec Logo -->
            <div class="mb-8">
                <img src="/public/images/logo-black.png" alt="Aztec Sinar" class="w-32 h-auto" />
            </div>
            
            <!-- 404 Error -->
            <div class="text-6xl font-bold text-gray-900 dark:text-white mb-4">404</div>
            <div class="text-2xl font-semibold text-gray-700 dark:text-gray-300 mb-8">Not Found</div>
            
            <!-- Go Home Button -->
            <Button 
                label="Go to Home" 
                icon="pi pi-home" 
                @click="goToHome"
                class="px-8 py-3 text-lg"
            />
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

const goToHome = () => {
    router.push('/');
};
</script>
