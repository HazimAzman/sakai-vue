<template>
    <div>
        <div class="flex justify-content-between align-items-center mb-3">
            <h6 class="m-0">Service Management</h6>
            <Button class="ml-3" label="Add Service" icon="pi pi-plus" @click="openNew" />
        </div>

        <DataTable :value="services" :loading="loading" dataKey="id" responsiveLayout="scroll" 
                   :paginator="true" :rows="10" paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                   :rowsPerPageOptions="[5,10,25]">
            <Column field="id" header="ID" style="width: 80px" />
            <Column field="title" header="Title" />
            <Column field="description" header="Description" />
            <Column header="Image" style="width: 100px">
                <template #body="{ data }">
                    <img v-if="data.image_path" :src="data.image_path" :alt="data.title" 
                         class="w-4rem h-4rem object-cover border-round" />
                    <span v-else class="text-500">No image</span>
                </template>
            </Column>
            <Column header="Actions" style="width: 160px">
                <template #body="{ data }">
                    <div class="flex gap-2">
                        <Button icon="pi pi-pencil" size="small" @click="editService(data)" />
                        <Button icon="pi pi-trash" size="small" severity="danger" 
                                :loading="deleting" :disabled="deleting" 
                                @click="confirmDelete(data)" />
                    </div>
                </template>
            </Column>
        </DataTable>

        <Dialog v-model:visible="dialogVisible" :modal="true" :header="currentService?.id ? 'Edit Service' : 'Add Service'" 
                :style="{ width: '600px' }" class="p-fluid">
            <div class="mb-3 field">
                <label class="mr-3" for="title">Service Title *</label>
                <InputText id="title" v-model="form.title" :class="{ 'p-invalid': !form.title }" />
                <small v-if="!form.title" class="ml-3 p-error">Title is required.</small>
            </div>
            
            <div class="mb-3 field">
                <label class="mr-3" for="description">Description *</label>
                <Textarea id="description" v-model="form.description" rows="3" 
                         :class="{ 'p-invalid': !form.description }" />
                <small v-if="!form.description" class="ml-3 p-error">Description is required.</small>
            </div>
            
            <div class="mb-3 field">
                <label class="mr-3" for="image_path">Service Image *</label>
                <FileUpload id="image_path" 
                           mode="basic" 
                           name="image" 
                           accept="image/*" 
                           :maxFileSize="5000000"
                           @select="onImageSelect"
                           :auto="true"
                           chooseLabel="Choose Image"
                           :class="{ 'p-invalid': !form.image_path }" />
                <small v-if="!form.image_path" class="ml-3 p-error">Service Image is required.</small>
                <small class="ml-3 text-500">Upload an image for the service (max 5MB)</small>
                
                <!-- Preview uploaded image -->
                <div v-if="form.image_path" class="mt-3">
                    <img :src="form.image_path" :alt="form.title" 
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
                <span>Are you sure you want to delete "{{ serviceToDelete?.title }}"?</span>
            </div>
            
            <template #footer>
                <Button label="Cancel" class="p-button-text" icon="pi pi-times" @click="deleteDialogVisible = false" />
                <Button label="Delete" icon="pi pi-trash" severity="danger" @click="executeDelete" :loading="deleting" />
            </template>
        </Dialog>
    </div>
</template>

<script setup>
import { useNotifications } from '@/composables/useNotifications.js';
import { ApiService } from '@/service/ApiService.js';
import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import FileUpload from 'primevue/fileupload';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import { onMounted, reactive, ref } from 'vue';

const { success, error } = useNotifications();

const services = ref([]);
const loading = ref(false);
const saving = ref(false);
const deleting = ref(false);
const dialogVisible = ref(false);
const deleteDialogVisible = ref(false);
const currentService = ref(null);
const serviceToDelete = ref(null);
const selectedFile = ref(null);
const form = reactive({ 
    title: '', 
    description: '', 
    image_path: ''
});

const resetForm = () => {
    form.title = '';
    form.description = '';
    form.image_path = '';
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
    formData.append('category', 'service');
    
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

const loadServices = async () => {
    loading.value = true;
    try {
        const response = await ApiService.getServices();
        services.value = response.data || response;
    } catch (e) {
        services.value = [];
        console.error('Failed to load services:', e);
    } finally {
        loading.value = false;
    }
};

const openNew = () => {
    currentService.value = null;
    selectedFile.value = null;
    resetForm();
    dialogVisible.value = true;
};

const editService = (service) => {
    currentService.value = service;
    selectedFile.value = null;
    form.title = service.title || '';
    form.description = service.description || '';
    form.image_path = service.image_path || '';
    dialogVisible.value = true;
};

const save = async () => {
    // Validate form
    if (!form.title || !form.description || !form.image_path) {
        error('Validation Error', 'Please fill in all required fields');
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
            title: form.title,
            description: form.description,
            image_path: imagePath
        };
        
        if (currentService.value?.id) {
            await ApiService.updateService(currentService.value.id, formData);
            success('Success', 'Service updated successfully');
        } else {
            await ApiService.createService(formData);
            success('Success', 'Service created successfully');
        }
        await loadServices();
        dialogVisible.value = false;
    } catch (e) {
        console.error('Failed to save service:', e);
        error('Error', 'Failed to save service');
    } finally {
        saving.value = false;
    }
};

const confirmDelete = (service) => {
    if (deleting.value) return; // Prevent multiple delete operations
    serviceToDelete.value = service;
    deleteDialogVisible.value = true;
};

const executeDelete = async () => {
    if (deleting.value || !serviceToDelete.value) return;
    
    deleting.value = true;
    
    try {
        await ApiService.deleteService(serviceToDelete.value.id);
        success('Success', 'Service deleted successfully');
        await loadServices();
        deleteDialogVisible.value = false;
        serviceToDelete.value = null;
    } catch (e) {
        console.error('Failed to delete service:', e);
        error('Error', 'Failed to delete service');
    } finally {
        deleting.value = false;
    }
};

onMounted(loadServices);
</script>

<style scoped>
.field { 
    margin-bottom: 1rem; 
}

.p-invalid {
    border-color: #e24c4c;
}
</style>
