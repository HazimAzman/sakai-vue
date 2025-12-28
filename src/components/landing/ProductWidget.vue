<template>
    <div id="product" class="py-6 px-6 lg:px-20 mx-0 my-12 lg:mx-20">
        <div class="text-center mb-16">
            <div class="text-surface-900 dark:text-surface-0 font-bold mb-4 text-5xl">Product</div>
            <div class="h-1 w-16 bg-primary-500 mx-auto mb-4"></div>
            <span class="text-surface-600 dark:text-surface-300 text-2xl">People behind your great experience.</span>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="text-center py-12">
            <div class="text-red-500 text-lg">Failed to load products. Please try again later.</div>
        </div>

        <!-- Product Brand Logos Grid -->
        <div v-else class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4">
            <template v-for="(product, index) in products" :key="`product-${index}`">
            <RouterLink
                v-if="computeTo(product)"
                :to="computeTo(product)"
                class="group relative block rounded-lg overflow-hidden"
            >
                <div class="bg-white dark:bg-surface-800 rounded-lg shadow-md p-4 text-center transition-all duration-300 group-hover:shadow-xl group-hover:bg-primary-50 dark:group-hover:bg-surface-700">
                    <img 
                        :src="product.image_url" 
                        :alt="product.name" 
                        class="h-16 mx-auto mb-4 object-contain transition-transform duration-300 group-hover:scale-105"
                        @error="handleImageError"
                    />
                    <div class="text-sm font-semibold text-surface-900 dark:text-surface-0">{{ product.name }}</div>
                    <div class="text-xs text-surface-600 dark:text-surface-400 mt-1">{{ product.category }}</div>

                    <!-- Hover overlay CTA -->
                    <div class="pointer-events-none absolute inset-0 flex items-center justify-center bg-black/0 group-hover:bg-black/30 transition-colors duration-300">
                        <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white text-sm font-semibold px-3 py-1 rounded-full bg-primary-500/90">Visit product page</span>
                    </div>
                </div>
            </RouterLink>
            <div v-else class="bg-white dark:bg-surface-800 rounded-lg shadow-md p-4 text-center opacity-80">
                <img 
                    :src="product.image_url" 
                    :alt="product.name" 
                    class="h-16 mx-auto mb-4 object-contain"
                    @error="handleImageError"
                />
                <div class="text-sm font-semibold text-surface-900 dark:text-surface-0">{{ product.name }}</div>
                <div class="text-xs text-surface-600 dark:text-surface-400 mt-1">{{ product.category }}</div>
            </div>
            </template>
        </div>


    </div>
</template>

<style scoped>
/* Grid styling */
.grid {
    display: grid;
}

.grid-cols-2 {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

@media (min-width: 768px) {
    .md\:grid-cols-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}

@media (min-width: 1024px) {
    .lg\:grid-cols-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}
</style>

<script setup>
import { ApiService } from '@/service/ApiService.js';
import { onMounted, ref } from 'vue';
import { brands, normalizeBrand } from '@/assets/productLines.js';

const products = ref([]);
const loading = ref(true);
const error = ref(false);


const loadProducts = async () => {
    try {
        loading.value = true;
        error.value = false;
        
        const response = await ApiService.getProducts();
        products.value = response.data || response;
        
        // If no products, show empty state
        if (!products.value || products.value.length === 0) {
            products.value = [];
        }
    } catch (err) {
        console.error('Failed to load products:', err);
        error.value = true;
        products.value = [];
    } finally {
        loading.value = false;
    }
};

const handleImageError = (event) => {
    const img = event.target;
    if (img.__fallbackApplied) return; // prevent infinite loop
    img.__fallbackApplied = true;
    img.src = '/images/placeholder-product.png';
};

onMounted(() => {
    loadProducts();
});

// Determine where to route when clicking a product brand card
const computeTo = (product) => {
    const name = String(product?.name || '');
    let slug = normalizeBrand(name);
    
    // Brand name mappings (handle legacy names)
    const brandMappings = {
        'baxvision': 'raxvision'
    };
    if (brandMappings[slug]) {
        slug = brandMappings[slug];
    }
    
    // Known brand slugs that should route to brand page
    const knownBrands = ['hettich', 'hettich-lab-technology', 'scilab', 'thermofisher', 'thermofisher-scientific', 'raxvision'];
    
    if (brands[slug] || knownBrands.includes(slug)) {
        // Go to brand page that lists only that brand's lines
        return `/products/${slug}`;
    }
    // Fallback: if API has an id, go to generic product detail
    return product?.id ? `/products/${product.id}` : null;
};
</script>