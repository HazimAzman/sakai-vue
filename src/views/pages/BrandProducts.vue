<template>
    <div class="min-h-screen bg-surface-50 dark:bg-surface-900 py-10 px-6 lg:px-20">
        <div class="max-w-6xl mx-auto">
            <RouterLink to="/" class="text-primary-600 hover:underline">← Back to home</RouterLink>
            <div class="mt-4 text-2xl font-bold">{{ brand?.name || brandSlug.toUpperCase() }}</div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left: Product lines list for brand -->
                <aside class="md:col-span-1 bg-white dark:bg-surface-800 rounded-xl shadow-md">
                    <div class="p-4 border-b border-surface-200 dark:border-surface-700 font-semibold">Product lines</div>
                    <ul class="divide-y divide-surface-200 dark:divide-surface-700">
                        <li v-for="line in brand?.lines || []" :key="line.slug || line.name">
                            <RouterLink
                                :to="`/products/${brandKey}/${normalize(line.slug || line.name)}`"
                                class="block p-4 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors"
                                :class="{ 'bg-primary-50 dark:bg-surface-700': isSelected(line) }"
                            >
                                <div class="text-sm font-semibold">{{ line.name }}</div>
                                <div class="text-xs text-surface-600 dark:text-surface-400">{{ line.summary }}</div>
                            </RouterLink>
                        </li>
                    </ul>
                </aside>

                <!-- Right: Line details -->
                <section class="md:col-span-2">
                    <div v-if="!selectedLine" class="text-surface-600 dark:text-surface-300">
                        Select a product line from the list.
                    </div>

                    <div v-else class="bg-white dark:bg-surface-800 rounded-xl shadow-md p-8">
                        <h1 class="text-2xl font-bold">{{ selectedLine.name }}</h1>
                        <p class="mt-2 text-surface-700 dark:text-surface-300">{{ selectedLine.summary }}</p>

                        <div v-if="selectedLine.highlights?.length" class="mt-6">
                            <h2 class="text-lg font-semibold mb-2">Highlights</h2>
                            <ul class="list-disc pl-6 space-y-1">
                                <li v-for="(h, i) in selectedLine.highlights" :key="i">{{ h }}</li>
                            </ul>
                        </div>

                        <div v-if="selectedLine.brochureUrl" class="mt-6 flex gap-3">
                            <a :href="selectedLine.brochureUrl" download class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-medium px-4 py-2 rounded-md">
                                <i class="pi pi-download"></i>
                                Download brochure
                            </a>
                            <a :href="selectedLine.brochureUrl" target="_blank" rel="noopener" class="inline-flex items-center gap-2 border border-primary-600 text-primary-700 hover:bg-primary-50 font-medium px-4 py-2 rounded-md">
                                <i class="pi pi-external-link"></i>
                                Open brochure
                            </a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>

<script setup>
import { findBrand, findLine, normalizeBrand } from '@/assets/productLines.js';
import { computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();
const brandSlug = computed(() => String(route.params.brand || ''));
const brand = computed(() => findBrand(brandSlug.value));
const brandKey = computed(() => brand.value?.key || brandSlug.value);
const normalize = (s) => normalizeBrand(s);

const selectedLine = computed(() => {
    const lineSlug = String(route.params.line || '');
    if (!lineSlug || !brandKey.value) return null;
    return findLine(brandKey.value, lineSlug);
});

const isSelected = (line) => {
    const lineSlug = String(route.params.line || '');
    return normalize(line.slug || line.name) === normalize(lineSlug);
};

// Default to first product line (e.g., Hettich Lab Technology -> EBA200) when /products/:brand has no :line
onMounted(() => {
    const hasLine = Boolean(route.params.line);
    if (!hasLine && brand.value?.lines?.length) {
        const first = brand.value.lines[0];
        const lineSlug = normalize(first.slug || first.name);
        router.replace(`/products/${brandKey.value}/${lineSlug}`);
    }
});
</script>

<style scoped>
.grid { display: grid; }
.grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
@media (min-width: 768px) {
  .md\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
  .md\:col-span-1 { grid-column: span 1 / span 1; }
  .md\:col-span-2 { grid-column: span 2 / span 2; }
}
</style>


