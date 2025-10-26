<template>
    <div id="about" class="py-6 px-6 lg:px-20 my-2 md:my-6 bg-gray-50 dark:bg-surface-800 relative overflow-hidden">
        <!-- Background wavy lines -->
        <div class="absolute top-0 right-0 w-96 h-96 opacity-10">
            <svg viewBox="0 0 400 400" class="w-full h-full">
                <path d="M0,100 Q100,50 200,100 T400,100 L400,0 L0,0 Z" fill="currentColor" class="text-primary-500"/>
                <path d="M0,200 Q100,150 200,200 T400,200 L400,100 L0,100 Z" fill="currentColor" class="text-primary-300"/>
                <path d="M0,300 Q100,250 200,300 T400,300 L400,200 L0,200 Z" fill="currentColor" class="text-primary-400"/>
            </svg>
        </div>

        <div class="relative z-10">
            <!-- Header -->
            <div class="mb-12">
           
                <div class="text-surface-900 dark:text-surface-0 font-bold text-3xl mb-4">About</div>
               
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="text-center py-12">
                <div class="text-red-500 text-lg">Failed to load about content. Please try again later.</div>
            </div>

            <!-- Content Grid -->
            <div v-else class="grid grid-cols-12 gap-8 items-start">
                <!-- Left Column - Text Content -->
                <div class="col-span-12 lg:col-span-8">
                    <div class="mb-8">
                        <h2 class="text-surface-900 dark:text-surface-0 font-bold text-3xl mb-4">{{ aboutContent.title || 'BRINGING YOUR LAB SOLUTION & NEEDS' }}</h2>
                        <div class="h-1 w-16 bg-primary-500 mb-6"></div>
                    </div>

                    <div class="space-y-6 text-surface-700 dark:text-surface-300 leading-relaxed">
                        <div v-html="aboutContent.content"></div>
                    </div>
                </div>

                <!-- Right Column - CEO Photo -->
                <div class="col-span-12 lg:col-span-4 flex justify-center lg:justify-end">
                    <div class="text-center">
                        <img 
                            :src="aboutContent.image_url || '/images/about/azman-yunus.png'" 
                            :alt="aboutContent.name || 'Azman Yunus'" 
                            class="w-80 h-100 object-cover rounded-lg shadow-lg mb-4"
                            @error="handleImageError"
                        />
                        <div class="text-surface-900 dark:text-surface-0 font-semibold text-xl">{{ aboutContent.name || 'Azman Yunus' }}</div>
                        <div class="text-surface-600 dark:text-surface-400 text-lg">{{ aboutContent.position || 'Chief Executive Officer' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ApiService } from '@/service/ApiService.js';
import { onMounted, ref } from 'vue';

const aboutContent = ref({});
const loading = ref(true);
const error = ref(false);

const defaultDescription = `
    <p class="text-lg">
        AZTEC SINAR SDN BHD was incorporated in 2004 and has grown to become a leading supplier of scientific instrumentation to research, lab services, education, clinical, and pathology labs throughout Malaysia. We offer sales and rentals of test and measurement, monitoring, and general scientific equipment.
    </p>

    <p class="text-lg">
        AZTEC SINAR distributes and provides services across Malaysia, including Sabah and Sarawak. Our clientele includes universities, education colleges, training centers, research centers, service laboratories, and pathology labs under various Malaysian government ministries, as well as selected private industries like factories and test labs.
    </p>

    <p class="text-lg">
        AZTEC SINAR's capabilities include equipment installation (analytical instruments, test measurement, environmental monitoring devices), product training, and professional services in collaboration with local and international partners. Our project experience spans government and GLC sectors, including oil and gas, palm mills, bio-gas plants, water treatment, clinical and pathology labs, life science and molecular research, manufacturing, and service lab clients throughout Malaysia, Sabah, and Sarawak.
    </p>
`;

const loadAboutContent = async () => {
    try {
        loading.value = true;
        error.value = false;
        
        const response = await ApiService.getAbout();
        const aboutData = response.data || response;
        
        // If we have data, use the first item
        if (aboutData && aboutData.length > 0) {
            aboutContent.value = aboutData[0];
        } else {
            aboutContent.value = {};
        }
    } catch (err) {
        console.error('Failed to load about content:', err);
        error.value = true;
        aboutContent.value = {};
    } finally {
        loading.value = false;
    }
};

const handleImageError = (event) => {
    // Set a placeholder image if the original fails to load
    event.target.src = '/images/about/azman-yunus.png';
};

onMounted(() => {
    loadAboutContent();
});
</script>
