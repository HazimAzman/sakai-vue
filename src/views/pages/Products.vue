<template>
    <div class="min-h-screen bg-surface-50 dark:bg-surface-900 py-10 px-6 lg:px-20">
        <div class="max-w-6xl mx-auto">
            <RouterLink to="/" class="text-primary-600 hover:underline">← Back to home</RouterLink>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left: List -->
                <aside class="md:col-span-1 bg-white dark:bg-surface-800 rounded-xl shadow-md">
                    <div class="p-4 border-b border-surface-200 dark:border-surface-700 font-semibold">Products</div>
                    <ul class="divide-y divide-surface-200 dark:divide-surface-700">
                        <li v-for="p in products" :key="p.id || p.name">
                            <RouterLink
                                :to="p.id ? `/products/${p.id}` : '/products'"
                                class="flex items-center gap-3 p-4 hover:bg-surface-100 dark:hover:bg-surface-700 transition-colors"
                                :class="{ 'bg-primary-50 dark:bg-surface-700': String(selectedId) === String(p.id) }"
                                @click="select(p)"
                            >
                                <img :src="p.image_url" :alt="p.name" class="w-10 h-10 object-contain" @error="handleImageError" />
                                <div>
                                    <div class="text-sm font-semibold">{{ p.name }}</div>
                                    <div class="text-xs text-surface-600 dark:text-surface-400">{{ p.category }}</div>
                                </div>
                            </RouterLink>
                        </li>
                    </ul>
                </aside>

                <!-- Right: Detail -->
                <section class="md:col-span-2">
                    <div v-if="loadingDetail" class="flex justify-center items-center py-20">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
                    </div>

                    <div v-else-if="!selectedProduct" class="text-surface-600 dark:text-surface-300">
                        Select a product from the list to view details.
                    </div>

                    <div v-else class="bg-white dark:bg-surface-800 rounded-xl shadow-md overflow-hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2">
                            <div class="p-10 flex items-center justify-center bg-surface-100 dark:bg-surface-700">
                                <img :src="selectedProduct.image_url || selectedProduct.image_path" :alt="selectedProduct.name" class="max-h-72 object-contain" @error="handleImageError" />
                            </div>
                            <div class="p-8">
                                <h1 class="text-2xl font-bold mb-2">{{ selectedProduct.name }}</h1>
                                <div class="text-sm text-surface-600 dark:text-surface-400 mb-4">{{ selectedProduct.category }}</div>
                                <p class="leading-relaxed whitespace-pre-line">{{ selectedProduct.description }}</p>

                                <!-- Hettich Lab Technology EBA 200-200S custom content example -->
                                <div v-if="isHettichEba200" class="mt-6 border-t border-surface-200 dark:border-surface-700 pt-6">
                                    <h2 class="text-xl font-semibold mb-2">Hettich Lab Technology EBA 200-200S Highlights</h2>
                                    <ul class="list-disc pl-6 space-y-1">
                                        <li>Compact centrifuge accessory for reliable liquid handling workflows.</li>
                                        <li>Designed for consistent performance and everyday lab use.</li>
                                        <li>Refer to the brochure for detailed specifications and configurations.</li>
                                    </ul>
                                    <div class="mt-4 flex gap-3">
                                        <a :href="brochureUrl" download class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-medium px-4 py-2 rounded-md">
                                            <i class="pi pi-download"></i>
                                            Download brochure
                                        </a>
                                        <a :href="brochureUrl" target="_blank" rel="noopener" class="inline-flex items-center gap-2 border border-primary-600 text-primary-700 hover:bg-primary-50 font-medium px-4 py-2 rounded-md">
                                            <i class="pi pi-external-link"></i>
                                            Open brochure
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ApiService } from '@/service/ApiService.js';
import { onMounted, ref, watch, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

const products = ref([]);
const selectedProduct = ref(null);
const selectedId = ref(route.params.id || '');
const loadingList = ref(true);
const loadingDetail = ref(false);

const brochureUrl = '/catalog/EBA200-200S_EN (1).pdf';

const loadProducts = async () => {
    loadingList.value = true;
    try {
        const resp = await ApiService.getProducts();
        products.value = resp.data || resp || [];
    } catch (_) {
        products.value = [];
    } finally {
        loadingList.value = false;
    }
};

const loadDetail = async (id) => {
    if (!id) {
        selectedProduct.value = null;
        return;
    }
    loadingDetail.value = true;
    try {
        const resp = await ApiService.getProduct(id);
        selectedProduct.value = resp.data || resp || null;
    } catch (_) {
        selectedProduct.value = null;
    } finally {
        loadingDetail.value = false;
    }
};

const select = (p) => {
    if (!p?.id) return;
    selectedId.value = p.id;
    router.push(`/products/${p.id}`);
};

const isHettichEba200 = computed(() => {
    if (!selectedProduct.value) return false;
    const name = String(selectedProduct.value.name || '').toLowerCase();
    return (name.includes('hettich') || name.includes('hettich lab technology')) && (name.includes('eba 200') || name.includes('eba200'));
});

watch(() => route.params.id, (id) => {
    selectedId.value = id || '';
    loadDetail(selectedId.value);
});

onMounted(async () => {
    await loadProducts();
    await loadDetail(selectedId.value);
});

const handleImageError = (e) => {
    const img = e.target;
    if (img.__fallbackApplied) return;
    img.__fallbackApplied = true;
    img.src = '/images/placeholder-product.png';
};
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


