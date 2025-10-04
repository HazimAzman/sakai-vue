<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import VideoModal from './VideoModal.vue';

const props = defineProps({
    slides: {
        type: Array,
        default: () => []
    },
    autoplayMs: {
        type: Number,
        default: 0 // 0 disables autoplay
    }
});

const currentIndex = ref(0);
const total = computed(() => props.slides.length);
const showVideoModal = ref(false);

// Video configuration - replace with your actual video details
const videoId = ref('dQw4w9WgXcQ'); // Replace with your YouTube video ID

function next() {
    if (total.value === 0) return;
    currentIndex.value = (currentIndex.value + 1) % total.value;
}

function prev() {
    if (total.value === 0) return;
    currentIndex.value = (currentIndex.value - 1 + total.value) % total.value;
}

function openVideo() {
    showVideoModal.value = true;
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closeVideo() {
    showVideoModal.value = false;
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

let timerId;
onMounted(() => {
    if (props.autoplayMs > 0 && total.value > 1) {
        timerId = setInterval(next, props.autoplayMs);
    }
});

onBeforeUnmount(() => {
    if (timerId) clearInterval(timerId);
});
</script>

<template>
    <section id='hero' class="px-6 md:px-12 lg:px-20 py-10" 
    style="background: linear-gradient(0deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.2)), radial-gradient(77.36% 256.97% at 77.36% 57.52%, rgb(238, 239, 175) 0%, rgb(195, 227, 250) 100%); clip-path: ellipse(150% 87% at 93% 13%)">
        <div class="bg-surface-0 dark:bg-surface-900 rounded-border shadow-sm">
            <div v-if="total === 0" class="p-8 text-center text-surface-500">No slides</div>
            <div v-else class="grid grid-cols-12 gap-0 min-h-[400px] lg:min-h-[500px]">
                <div class="col-span-12 lg:col-span-6 flex items-center justify-center bg-gray-50 dark:bg-gray-800 p-4">
                    <img :src="props.slides[currentIndex].image" :alt="props.slides[currentIndex].title" class="w-full h-auto max-h-[400px] lg:max-h-[500px] object-contain rounded-lg shadow-sm" />
                </div>
                <div class="col-span-12 lg:col-span-6 flex items-center">
                    <div class="p-8 md:p-12 w-full">
                        <h2 class="text-4xl md:text-5xl font-extrabold text-surface-900 dark:text-surface-0">{{ props.slides[currentIndex].title }}</h2>
                        <div class="h-1 w-16 bg-primary-500 my-4"></div>
                        <p class="text-surface-600 dark:text-surface-300 text-lg md:text-xl leading-8 max-w-prose">
                            {{ props.slides[currentIndex].description }}
                        </p>
                        <div v-if="props.slides[currentIndex].link" class="mt-6">
                            <button @click="openVideo" class="text-primary-500 underline hover:text-primary-600 transition-colors cursor-pointer bg-transparent border-none p-0 text-left">
                                Our Video
                            </button>
                        </div>

                        <div class="mt-10 flex gap-2">
                            <button type="button" class="w-12 h-12 border border-surface-200 rounded-md flex items-center justify-center" @click="prev">
                                <i class="pi pi-chevron-left"></i>
                            </button>
                            <button type="button" class="w-12 h-12 border border-surface-200 rounded-md flex items-center justify-center" @click="next">
                                <i class="pi pi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Video Modal -->
        <VideoModal 
            :visible="showVideoModal" 
            :video-id="videoId"
            @close="closeVideo" 
        />
    </section>
    
</template>

<style scoped>
/* Ensure proper display on all devices */
.grid {
    display: grid;
}

/* Mobile responsiveness */
@media (max-width: 1024px) {
    .grid {
        grid-template-columns: 1fr;
    }
    
    .col-span-12.lg\:col-span-6 {
        grid-column: span 12;
    }
}

/* Ensure images are fully visible */
img {
    max-width: 100%;
    height: auto;
    display: block;
}

/* Smooth transitions for carousel */
.carousel-transition {
    transition: all 0.3s ease-in-out;
}
</style>
