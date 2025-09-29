<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

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

function next() {
    if (total.value === 0) return;
    currentIndex.value = (currentIndex.value + 1) % total.value;
}

function prev() {
    if (total.value === 0) return;
    currentIndex.value = (currentIndex.value - 1 + total.value) % total.value;
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
            <div v-else class="grid grid-cols-12 gap-0">
                <div class="col-span-12 lg:col-span-6">
                    <img :src="props.slides[currentIndex].image" :alt="props.slides[currentIndex].title" class="w-full h-full object-cover" />
                </div>
                <div class="col-span-12 lg:col-span-6 flex items-center">
                    <div class="p-8 md:p-12 w-full">
                        <h2 class="text-4xl md:text-5xl font-extrabold text-surface-900 dark:text-surface-0">{{ props.slides[currentIndex].title }}</h2>
                        <div class="h-1 w-16 bg-primary-500 my-4"></div>
                        <p class="text-surface-600 dark:text-surface-300 text-lg md:text-xl leading-8 max-w-prose">
                            {{ props.slides[currentIndex].description }}
                        </p>
                        <div v-if="props.slides[currentIndex].link" class="mt-6">
                            <a :href="props.slides[currentIndex].link" class="text-primary-500 underline">Our Video</a>
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
    </section>
    
</template>


