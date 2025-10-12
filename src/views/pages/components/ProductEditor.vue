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
                        <Button icon="pi pi-trash" size="small" severity="danger" 
                                :loading="deleting" :disabled="deleting" 
                                @click="confirmDelete(data)" />
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
                <label class="mr-4" for="image_path">Product Image *</label>
                <FileUpload id="image_path" 
                           mode="basic" 
                           name="image" 
                           accept="image/*" 
                           :maxFileSize="5000000"
                           @select="onImageSelect"
                           :auto="true"
                           chooseLabel="Choose Image"
                           :class="{ 'p-invalid': !form.image_path }" />
                <small v-if="!form.image_path" class="p-error ml-4">Product Image is required.</small>
                <small class="text-500 ml-4">Upload an image for the product (max 5MB)</small>
                
                <!-- Preview uploaded image -->
                <div v-if="form.image_path" class="mt-3">
                    <img :src="form.image_path" :alt="form.name" 
                         class="w-8rem h-8rem object-cover border-round border-1 border-300" />
                </div>
            </div>

            <template #footer>
                <Button label="Cancel" class="p-button-text" icon="pi pi-times" @click="dialogVisible = false" />
                <Button label="Save" icon="pi pi-check" @click="save" :loading="saving" />
            </template>
        </Dialog>

        <!-- Custom Delete Confirmation Dialog -->
        <Dialog v-model:visible="deleteDialogVisible" :modal="true" header="Confirm Delete" 
                :style="{ width: '400px' }" class="p-fluid">
            <div class="flex align-items-center">
                <i class="pi pi-exclamation-triangle text-orange-500 text-2xl mr-3"></i>
                <span>Are you sure you want to delete "{{ productToDelete?.name }}"?</span>
            </div>
            
            <template #footer>
                <Button label="Cancel" class="p-button-text" icon="pi pi-times" @click="deleteDialogVisible = false" />
                <Button label="Delete" icon="pi pi-trash" severity="danger" @click="executeDelete" :loading="deleting" />
            </template>
        </Dialog>
    </div>
</template>

<script setup>
import { useNotifications } from '@/composables/useNotifications';
import { ApiService } from '@/service/ApiService.js';
import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import FileUpload from 'primevue/fileupload';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import { onMounted, reactive, ref } from 'vue';

const { success, error, warning } = useNotifications();

const products = ref([]);
const loading = ref(false);
const saving = ref(false);
const deleting = ref(false);
const dialogVisible = ref(false);
const deleteDialogVisible = ref(false);
const currentProduct = ref(null);
const productToDelete = ref(null);
const selectedFile = ref(null);
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

const onImageSelect = (event) => {
    const file = event.files[0];
    if (file) {
        selectedFile.value = file;
        form.image_path = URL.createObjectURL(file);
    }
};

const uploadImage = async (file) => {
    const formData = new FormData();
    formData.append('image', file);
    formData.append('category', 'product');
    
    try {
        const response = await fetch('/api/upload/image', {
            method: 'POST',
            body: formData
        });
        
        if (!response.ok) {
            throw new Error('Failed to upload image');
        }
        
        const result = await response.json();
        return result.data?.path || result.path || result.url;
    } catch (error) {
        console.error('Image upload failed:', error);
        throw error;
    }
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
    selectedFile.value = null;
    resetForm();
    dialogVisible.value = true;
};

const editProduct = (product) => {
    currentProduct.value = product;
    selectedFile.value = null;
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
        let imagePath = form.image_path;
        
        // Upload image if a new file was selected
        if (selectedFile.value) {
            imagePath = await uploadImage(selectedFile.value);
        }
        
        const formData = {
            name: form.name,
            description: form.description,
            image_path: imagePath,
            category: form.category
        };
        
        if (currentProduct.value?.id) {
            await ApiService.updateProduct(currentProduct.value.id, formData);
            success('Product Updated', `"${form.name}" has been updated successfully`);
        } else {
            await ApiService.createProduct(formData);
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
    if (deleting.value) return; // Prevent multiple delete operations
    productToDelete.value = product;
    deleteDialogVisible.value = true;
};

const executeDelete = async () => {
    if (deleting.value || !productToDelete.value) return;
    
    deleting.value = true;
    
    try {
        await ApiService.deleteProduct(productToDelete.value.id);
        success('Product Deleted', `"${productToDelete.value.name}" has been deleted successfully`);
        await loadProducts();
        deleteDialogVisible.value = false;
        productToDelete.value = null;
    } catch (e) {
        console.error('Failed to delete product:', e);
        error('Delete Failed', 'Failed to delete product. Please try again.');
    } finally {
        deleting.value = false;
    }
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
