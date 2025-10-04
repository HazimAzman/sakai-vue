<template>
    <div class="card">
        <div class="flex justify-content-between align-items-center mb-5">
            <h5 class="m-0">Products from API</h5>
            <Button 
                label="Refresh" 
                icon="pi pi-refresh" 
                @click="loadProducts"
                :loading="loading"
            />
        </div>

        <!-- API Status -->
        <div class="mb-4">
            <Message 
                v-if="apiStatus === 'connected'" 
                severity="success" 
                :closable="false"
                content="Connected to Yii2 API Backend"
            />
            <Message 
                v-else-if="apiStatus === 'error'" 
                severity="error" 
                :closable="false"
                content="Failed to connect to API. Using fallback data."
            />
            <Message 
                v-else-if="apiStatus === 'loading'" 
                severity="info" 
                :closable="false"
                content="Loading products from API..."
            />
        </div>

        <!-- Products Grid -->
        <div v-if="products.length > 0" class="grid">
            <div 
                v-for="product in products" 
                :key="product.id" 
                class="col-12 md:col-6 lg:col-4"
            >
                <Card class="h-full">
                    <template #header>
                        <div class="flex align-items-center justify-content-center bg-gray-100 p-4">
                            <i class="pi pi-box text-6xl text-primary"></i>
                        </div>
                    </template>
                    <template #title>
                        {{ product.name }}
                    </template>
                    <template #content>
                        <p class="m-0 mb-3">{{ product.description }}</p>
                        <div class="flex align-items-center justify-content-between">
                            <span class="text-2xl font-bold text-primary">
                                ${{ product.price }}
                            </span>
                            <Tag 
                                :value="product.id" 
                                severity="info" 
                                icon="pi pi-tag"
                            />
                        </div>
                    </template>
                    <template #footer>
                        <div class="flex gap-2">
                            <Button 
                                label="View" 
                                icon="pi pi-eye" 
                                size="small"
                                @click="viewProduct(product)"
                            />
                            <Button 
                                label="Edit" 
                                icon="pi pi-pencil" 
                                size="small"
                                severity="secondary"
                                @click="editProduct(product)"
                            />
                        </div>
                    </template>
                </Card>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else-if="!loading" class="text-center py-8">
            <i class="pi pi-inbox text-6xl text-gray-400 mb-3"></i>
            <h3 class="text-gray-600">No products found</h3>
            <p class="text-gray-500">Try refreshing the page or check your API connection.</p>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-8">
            <ProgressSpinner />
            <p class="mt-3 text-gray-600">Loading products...</p>
        </div>

        <!-- Product Dialog -->
        <Dialog 
            v-model:visible="productDialog" 
            :header="selectedProduct?.name || 'Product Details'"
            :modal="true" 
            class="p-fluid"
            :style="{ width: '450px' }"
        >
            <div v-if="selectedProduct" class="field">
                <label for="name">Name</label>
                <InputText 
                    id="name" 
                    v-model="selectedProduct.name" 
                    required="true" 
                    autofocus 
                    :class="{ 'p-invalid': submitted && !selectedProduct.name }"
                />
            </div>
            <div v-if="selectedProduct" class="field">
                <label for="description">Description</label>
                <Textarea 
                    id="description" 
                    v-model="selectedProduct.description" 
                    required="true" 
                    rows="3" 
                    cols="20"
                />
            </div>
            <div v-if="selectedProduct" class="field">
                <label for="price">Price</label>
                <InputNumber 
                    id="price" 
                    v-model="selectedProduct.price" 
                    mode="currency" 
                    currency="USD" 
                    locale="en-US"
                />
            </div>
            <template #footer>
                <Button 
                    label="Cancel" 
                    icon="pi pi-times" 
                    class="p-button-text" 
                    @click="productDialog = false"
                />
                <Button 
                    label="Save" 
                    icon="pi pi-check" 
                    @click="saveProduct"
                />
            </template>
        </Dialog>
    </div>
</template>

<script setup>
import { ApiService } from '@/service/ApiService.js';
import { ProductService } from '@/service/ProductService.js';
import { onMounted, ref } from 'vue';

// Reactive data
const products = ref([]);
const loading = ref(false);
const apiStatus = ref('loading');
const productDialog = ref(false);
const selectedProduct = ref(null);
const submitted = ref(false);

// Load products from API
const loadProducts = async () => {
    loading.value = true;
    apiStatus.value = 'loading';
    
    try {
        // First try to get products from API
        const apiProducts = await ProductService.getProductsFromAPI();
        products.value = apiProducts;
        apiStatus.value = 'connected';
    } catch (error) {
        console.error('Error loading products:', error);
        // Fallback to local data
        products.value = ProductService.getProductsData();
        apiStatus.value = 'error';
    } finally {
        loading.value = false;
    }
};

// View product details
const viewProduct = (product) => {
    selectedProduct.value = { ...product };
    productDialog.value = true;
};

// Edit product
const editProduct = (product) => {
    selectedProduct.value = { ...product };
    productDialog.value = true;
};

// Save product
const saveProduct = async () => {
    submitted.value = true;
    
    if (selectedProduct.value.name && selectedProduct.value.description && selectedProduct.value.price) {
        try {
            if (selectedProduct.value.id) {
                // Update existing product
                await ApiService.updateProduct(selectedProduct.value.id, selectedProduct.value);
            } else {
                // Create new product
                await ApiService.createProduct(selectedProduct.value);
            }
            
            // Reload products
            await loadProducts();
            productDialog.value = false;
            selectedProduct.value = null;
        } catch (error) {
            console.error('Error saving product:', error);
        }
    }
};

// Load products on component mount
onMounted(() => {
    loadProducts();
});
</script>

<style scoped>
.card {
    margin: 1rem;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
}

@media (max-width: 768px) {
    .grid {
        grid-template-columns: 1fr;
    }
}
</style>
