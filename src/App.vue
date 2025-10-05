<script setup>
import NotificationContainer from '@/components/NotificationContainer.vue';
import { useTawkTo } from '@/composables/useTawkTo.js';
import { onMounted } from 'vue';

// Initialize Tawk.to management safely
onMounted(() => {
    useTawkTo();
});
</script>

<template>
    <router-view />
    <NotificationContainer />
</template>

<style scoped></style>
