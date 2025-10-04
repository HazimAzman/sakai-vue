<template>
    <div>
        <div class="flex justify-content-between align-items-center mb-3">
            <h6 class="m-0">Product Management</h6>
            <Button class='p-button-primary ml-3' label="Add Product" icon="pi pi-plus" @click="openNew" />
        </div>

        <DataTable :value="products" :loading="loading" dataKey="id" responsiveLayout="scroll" 
                   :paginator="true" :rows="10" paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                   :rowsPerPageOptions="[5,10,25]">
            <Column field="id" header="ID" style="width: 80px" />
            <Column field="name" header="Name" />
            <Column field="category" header="Category" />
            <Column field="description" header="Description" />
            <Column header="Image" style="width: 100px">
                <template #body="{ data }">
                    <img v-if="data.image_path" :src="data.image_path" :alt="data.name" 
                         class="w-4rem h-4rem object-cover border-round" />
                    <span v-else class="text-500">No image</span>
                </template>
            </Column>
            <Column header="Actions" style="width: 160px">
                <template #body="{ data }">
                    <div class="flex gap-2">
                        <Button icon="pi pi-pencil" size="small" @click="editProduct(data)" />
                        <Button icon="pi pi-trash" size="small" severity="danger" @click="confirmDelete(data)" />
                    </div>
                </template>
            </Column>
        </DataTable>

        <Dialog v-model:visible="dialogVisible" :modal="true" :header="currentProduct?.id ? 'Edit Product' : 'Add Product'" 
                :style="{ width: '600px' }" class="p-fluid">
            <div class="field">
                <label class="mr-4" for="name">Product Name *</label>
                <InputText id="name" v-model="form.name" :class="{ 'p-invalid': !form.name }" />
                <small v-if="!form.name" class="p-error ml-4">Name is required.</small>
            </div>
            
            <div class="field">
                <label class="mr-4" for="category">Category *</label>
                <InputText id="category" v-model="form.category" :class="{ 'p-invalid': !form.category }" />
                <small v-if="!form.category" class="p-error ml-4">Category is required.</small>
            </div>
            
            <div class="field">
                <label class="mr-4" for="description">Description *</label>
                <Textarea id="description" v-model="form.description" rows="3" 
                         :class="{ 'p-invalid': !form.description }" />
                <small v-if="!form.description" class="p-error ml-4">Description is required.</small>
            </div>
            
            <div class="field">
                <label class="mr-4" for="image_path">Image Path *</label>
                <InputText id="image_path" v-model="form.image_path" 
                          placeholder="/images/product/example-150.png"
                          :class="{ 'p-invalid': !form.image_path }" />
                <small v-if="!form.image_path" class="p-error ml-4">Image path is required.</small>
                <small class="text-500 ml-4">Enter the path to the product image (e.g., /images/product/brand-150.png)</small>
            </div>

            <template #footer>
                <Button label="Cancel" class="p-button-text" icon="pi pi-times" @click="dialogVisible = false" />
                <Button label="Save" icon="pi pi-check" @click="save" :loading="saving" />
            </template>
        </Dialog>

        <ConfirmDialog />
    </div>
</template>

<script setup>
import { useNotifications } from '@/composables/useNotifications';
import { ApiService } from '@/service/ApiService.js';
import { useConfirm } from 'primevue/useconfirm';
import { onMounted, reactive, ref } from 'vue';

const confirm = useConfirm();
const { success, error, warning } = useNotifications();

const products = ref([]);
const loading = ref(false);
const saving = ref(false);
const dialogVisible = ref(false);
const currentProduct = ref(null);
const form = reactive({ 
    name: '', 
    description: '', 
    image_path: '', 
    category: '' 
});

const resetForm = () => {
    form.name = '';
    form.description = '';
    form.image_path = '';
    form.category = '';
};

const loadProducts = async () => {
    loading.value = true;
    try {
        const response = await ApiService.getProducts();
        products.value = response.data || response;
    } catch (e) {
        products.value = [];
        console.error('Failed to load products:', e);
    } finally {
        loading.value = false;
    }
};

const openNew = () => {
    currentProduct.value = null;
    resetForm();
    dialogVisible.value = true;
};

const editProduct = (product) => {
    currentProduct.value = product;
    form.name = product.name || '';
    form.description = product.description || '';
    form.image_path = product.image_path || '';
    form.category = product.category || '';
    dialogVisible.value = true;
};

const save = async () => {
    // Validate form
    if (!form.name || !form.description || !form.image_path || !form.category) {
        warning('Validation Error', 'Please fill in all required fields');
        return;
    }

    saving.value = true;
    try {
        if (currentProduct.value?.id) {
            await ApiService.updateProduct(currentProduct.value.id, { ...form });
            success('Product Updated', `"${form.name}" has been updated successfully`);
        } else {
            await ApiService.createProduct({ ...form });
            success('Product Created', `"${form.name}" has been created successfully`);
        }
        await loadProducts();
        dialogVisible.value = false;
    } catch (e) {
        console.error('Failed to save product:', e);
        error('Save Failed', 'Failed to save product. Please try again.');
    } finally {
        saving.value = false;
    }
};

const confirmDelete = (product) => {
    confirm.require({
        message: `Are you sure you want to delete "${product.name}"?`,
        header: 'Confirm Delete',
        icon: 'pi pi-exclamation-triangle',
        rejectClass: 'p-button-secondary p-button-outlined',
        rejectLabel: 'Cancel',
        acceptLabel: 'Delete',
        accept: async () => {
            try {
                await ApiService.deleteProduct(product.id);
                success('Product Deleted', `"${product.name}" has been deleted successfully`);
                await loadProducts();
            } catch (e) {
                console.error('Failed to delete product:', e);
                error('Delete Failed', 'Failed to delete product. Please try again.');
            }
        }
    });
};

onMounted(loadProducts);
</script>

<style scoped>
.field { 
    margin-bottom: 1rem; 
}

.p-invalid {
    border-color: #e24c4c;
}
</style>
