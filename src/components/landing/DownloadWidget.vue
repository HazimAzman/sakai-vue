<script setup>
import { ref } from 'vue';

// List of brands to display in the table
const brands = ref([
    'Accutec',
    'Ametek',
    'Arcs',
    'Center',
    'Constance Lab',
    'Daihan',
    'Defelsko',
    'Eisen',
    'Eyela',
    'Hettich',
    'IKA',
    'Interscience',
    'Julabo',
    'Kibron',
    'MembraPure',
    'Mettler Toledo',
    'Milwaukee',
    'Minebea Intec',
    'Mitutoyo',
    'Nabertherm',
    'N-Biotech',
    'Optika',
    'Perkin Elmer',
    'RaxVision',
    'RCPAQAP',
    'Renishaw',
    'Rocker',
    'Sartorius',
    'Scilab',
    'Shimadzu',
    'Shimpo',
    'StereCo',
    'Thermo',
    'TIME',
    'Toptech',
    'Velp',
    'Wason Marlow',
    'Welch',
    'Yamamoto'
]);

// Map brand -> download URL (fill with real links later)
const brandToUrl = ref({});

const getUrl = (brand) => brandToUrl.value[brand] || '#';
</script>

<template>
    <div id="download" class="py-6 px-6 lg:px-20 mx-0 my-12 lg:mx-20">
        <div class="text-center mb-8">
            <div class="text-surface-900 dark:text-surface-0 font-bold mb-2 text-3xl md:text-4xl">Download Center</div>
            <div class="h-1 w-16 bg-primary-500 mx-auto mb-2"></div>
        </div>

        <div class="bg-white dark:bg-surface-800 rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-surface-100 dark:bg-surface-700">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-surface-700 dark:text-surface-200">Brand</th>
                        <th class="text-left px-4 py-3 font-semibold text-surface-700 dark:text-surface-200">Download</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(brand, index) in brands" :key="brand" :class="index % 2 === 0 ? 'bg-white dark:bg-surface-800' : 'bg-surface-50 dark:bg-surface-900'">
                        <td class="px-4 py-3 text-surface-900 dark:text-surface-0">{{ brand }}</td>
                        <td class="px-4 py-3">
                            <a :href="getUrl(brand)" target="_blank" rel="noopener" class="inline-flex items-center text-red-600 hover:text-red-700">
                                <!-- Download icon (inline SVG) -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path d="M12 3a1 1 0 011 1v8.586l2.293-2.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L11 12.586V4a1 1 0 011-1z"/>
                                    <path d="M4 15a1 1 0 011-1h14a1 1 0 011 1v3a3 3 0 01-3 3H7a3 3 0 01-3-3v-3z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>


