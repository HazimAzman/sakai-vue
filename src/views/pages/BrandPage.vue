<template>
    <div class="min-h-screen bg-surface-50 dark:bg-surface-900 py-10 px-6 lg:px-20">
        <div class="max-w-5xl mx-auto">
            <RouterLink to="/" class="text-primary-600 hover:underline">← Back to home</RouterLink>

            <div class="mt-6 bg-white dark:bg-surface-800 rounded-xl shadow-md p-8">
                <h1 class="text-2xl md:text-3xl font-bold text-surface-900 dark:text-surface-0 mb-2">{{ brandTitle }}</h1>
                <p class="text-surface-700 dark:text-surface-300">{{ subtitle }}</p>

                <div v-if="isHettich" class="mt-6">
                    <h2 class="text-xl font-semibold mb-4">Available Products</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <RouterLink to="/products/hettich/eba200-200s" class="block rounded-lg border border-surface-200 dark:border-surface-700 p-6 hover:shadow-lg transition-shadow">
                            <img src="/images/productpage/hettich/eba200/1.png" alt="EBA 200-200S" class="w-full h-auto rounded-lg mb-4 object-cover" />
                            <h3 class="text-lg font-semibold mb-2">EBA 200-200S</h3>
                            <p class="text-surface-600 dark:text-surface-400 text-sm">Hettich Lab Technology centrifuge accessory (EBA 200-200S series)</p>
                        </RouterLink>
                        <RouterLink to="/products/hettich/universal320-320r" class="block rounded-lg border border-surface-200 dark:border-surface-700 p-6 hover:shadow-lg transition-shadow">
                            <img src="/images/productpage/hettich/universal320/1.png" alt="UNIVERSAL 320-320R" class="w-full h-auto rounded-lg mb-4 object-cover" />
                            <h3 class="text-lg font-semibold mb-2">UNIVERSAL 320-320R</h3>
                            <p class="text-surface-600 dark:text-surface-400 text-sm">Hettich Lab Technology centrifuge accessory (UNIVERSAL 320-320R series)</p>
                        </RouterLink>
                        <RouterLink to="/products/hettich/rotofix32a" class="block rounded-lg border border-surface-200 dark:border-surface-700 p-6 hover:shadow-lg transition-shadow">
                            <img src="/images/productpage/hettich/rotofix32a/1.png" alt="ROTOFIX 32A" class="w-full h-auto rounded-lg mb-4 object-cover" />
                            <h3 class="text-lg font-semibold mb-2">ROTOFIX 32A</h3>
                            <p class="text-surface-600 dark:text-surface-400 text-sm">Hettich Lab Technology centrifuge accessory (ROTOFIX 32A series)</p>
                        </RouterLink>
                        <RouterLink to="/products/hettich/mikro200-200r" class="block rounded-lg border border-surface-200 dark:border-surface-700 p-6 hover:shadow-lg transition-shadow">
                            <img src="/images/productpage/hettich/mikro200/1.png" alt="MIKRO 200-200R" class="w-full h-auto rounded-lg mb-4 object-cover" />
                            <h3 class="text-lg font-semibold mb-2">MIKRO 200-200R</h3>
                            <p class="text-surface-600 dark:text-surface-400 text-sm">Hettich Lab Technology centrifuge accessory (MIKRO 200-200R series)</p>
                        </RouterLink>
                        <RouterLink to="/products/hettich/rotina380-380r" class="block rounded-lg border border-surface-200 dark:border-surface-700 p-6 hover:shadow-lg transition-shadow">
                            <img src="/images/productpage/hettich/rotina380/1.png" alt="ROTINA 380-380R" class="w-full h-auto rounded-lg mb-4 object-cover" />
                            <h3 class="text-lg font-semibold mb-2">ROTINA 380-380R</h3>
                            <p class="text-surface-600 dark:text-surface-400 text-sm">Hettich Lab Technology centrifuge accessory (ROTINA 380-380R series)</p>
                        </RouterLink>
                    </div>
                </div>

                <div v-else-if="isScilab" class="mt-6">
                    <h2 class="text-xl font-semibold mb-4">Available Products</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <RouterLink to="/products/scilab/wisebath" class="block rounded-lg border border-surface-200 dark:border-surface-700 p-6 hover:shadow-lg transition-shadow">
                            <img src="/images/productpage/scilab/wisebath/1.png" alt="Wisebath" class="w-full h-auto rounded-lg mb-4 object-cover" />
                            <h3 class="text-lg font-semibold mb-2">Wisebath</h3>
                            <p class="text-surface-600 dark:text-surface-400 text-sm">Scilab water bath accessory (Wisebath series)</p>
                        </RouterLink>
                    </div>
                </div>

                <div v-else-if="isThermoFisher" class="mt-6">
                    <h2 class="text-xl font-semibold mb-4">Available Products</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <RouterLink to="/products/thermofisher/genesys150" class="block rounded-lg border border-surface-200 dark:border-surface-700 p-6 hover:shadow-lg transition-shadow">
                            <img src="/images/productpage/thermo/Genesys/1.png" alt="Genesys 150" class="w-full h-auto rounded-lg mb-4 object-cover" />
                            <h3 class="text-lg font-semibold mb-2">Genesys 150</h3>
                            <p class="text-surface-600 dark:text-surface-400 text-sm">ThermoFisher Scientific Spectrophotometer (Genesys 150 series)</p>
                        </RouterLink>
                    </div>
                </div>

                <div v-else-if="isRaxVision" class="mt-6">
                    <h2 class="text-xl font-semibold mb-4">Available Products</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <RouterLink to="/products/raxvision/bi400" class="block rounded-lg border border-surface-200 dark:border-surface-700 p-6 hover:shadow-lg transition-shadow">
                            <img src="/images/productpage/raxvision/bi400/1.png" alt="BI400" class="w-full h-auto rounded-lg mb-4 object-cover" />
                            <h3 class="text-lg font-semibold mb-2">BI400</h3>
                            <p class="text-surface-600 dark:text-surface-400 text-sm">RAXVISION Biological Microscope (BI400 series)</p>
                        </RouterLink>
                    </div>
                </div>

                <div v-else class="mt-6 text-surface-600 dark:text-surface-300">
                    Product items for this brand are not available yet.
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const brand = computed(() => String(route.params.brand || ''));
const brandTitle = computed(() => brand.value.toUpperCase());

const isHettich = computed(() => brand.value.toLowerCase() === 'hettich' || brand.value.toLowerCase() === 'hettich-lab-technology');
const isScilab = computed(() => brand.value.toLowerCase() === 'scilab');
const isThermoFisher = computed(() => brand.value.toLowerCase() === 'thermofisher' || brand.value.toLowerCase() === 'thermofisher-scientific');
const isRaxVision = computed(() => brand.value.toLowerCase() === 'raxvision');
const subtitle = computed(() => {
    if (isHettich.value) return 'Centrifuge, Pipette & Incubator';
    if (isScilab.value) return 'Laboratory Equipment';
    if (isThermoFisher.value) return 'Freezer & Refrigerator';
    if (isRaxVision.value) return 'Microscope & Inspection System';
    return '';
});
</script>

<style scoped></style>
