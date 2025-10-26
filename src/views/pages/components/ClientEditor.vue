<template>
    <div>
        <div class="flex justify-content-between align-items-center mb-3">
            <h6 class="m-0">Client Management</h6>
            <Button class='p-button-primary ml-3' label="Add Client" icon="pi pi-plus" @click="openNew" />
        </div>

        <DataTable :value="clients" :loading="loading" dataKey="id" responsiveLayout="scroll" 
                   :paginator="true" :rows="10" paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                   :rowsPerPageOptions="[5,10,25]">
            <Column field="id" header="ID" style="width: 80px" />
            <Column field="name" header="Name" />
            <Column field="short_name" header="Short Name" />
            <Column header="Logo" style="width: 100px">
                <template #body="{ data }">
                    <img v-if="data.image_url" 
                         :src="data.image_url" 
                         :alt="data.name" 
                         class="w-4rem h-4rem object-cover border-round"
                         @error="handleImageError" />
                    <span v-else class="text-500">No logo</span>
                </template>
            </Column>
            <Column header="Actions" style="width: 160px">
                <template #body="{ data }">
                    <div class="flex gap-2">
                        <Button icon="pi pi-pencil" size="small" @click="editClient(data)" />
                        <Button icon="pi pi-trash" size="small" severity="danger" 
                                :loading="deleting" :disabled="deleting" 
                                @click="confirmDelete(data)" />
                    </div>
                </template>
            </Column>
        </DataTable>

        <Dialog v-model:visible="dialogVisible" :modal="true" :header="currentClient?.id ? 'Edit Client' : 'Add Client'" 
                :style="{ width: '600px' }" class="p-fluid">
            <div class="field">
                <label class="mr-4" for="name">Client Name *</label>
                <InputText id="name" v-model="form.name" :class="{ 'p-invalid': !form.name }" />
                <small v-if="!form.name" class="p-error ml-4">Client name is required.</small>
            </div>
            
            <div class="field">
                <label class="mr-4" for="short_name">Short Name *</label>
                <InputText id="short_name" v-model="form.short_name" :class="{ 'p-invalid': !form.short_name }" />
                <small v-if="!form.short_name" class="p-error ml-4">Short name is required.</small>
            </div>
            
            <div class="field">
                <label class="mr-4" for="logo_path">Client Logo *</label>
                <FileUpload id="logo_path" 
                           mode="basic" 
                           name="image" 
                           accept="image/*" 
                           :maxFileSize="5000000"
                           @select="onImageSelect"
                           :auto="true"
                           chooseLabel="Choose Logo"
                           :class="{ 'p-invalid': !form.logo_path }" />
                <small v-if="!form.logo_path" class="p-error ml-4">Client Logo is required.</small>
                <small class="text-500 ml-4">Upload a logo for the client (max 5MB)</small>
                
                <!-- Preview uploaded image -->
                <div v-if="form.logo_path" class="mt-3">
                    <img :src="form.logo_path" :alt="form.name" 
                         class="w-8rem h-8rem object-cover border-round border-1 border-300"
                         @error="handleImageError" />
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
                <span>Are you sure you want to delete "{{ clientToDelete?.name }}"?</span>
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
import { onMounted, reactive, ref } from 'vue';

const { success, error } = useNotifications();

const clients = ref([]);
const loading = ref(false);
const saving = ref(false);
const deleting = ref(false);
const dialogVisible = ref(false);
const deleteDialogVisible = ref(false);
const currentClient = ref(null);
const clientToDelete = ref(null);
const selectedFile = ref(null);
const form = reactive({ 
    name: '', 
    short_name: '', 
    logo_path: ''
});

const resetForm = () => {
    form.name = '';
    form.short_name = '';
    form.logo_path = '';
};

const onImageSelect = (event) => {
    const file = event.files[0];
    if (file) {
        selectedFile.value = file;
        form.logo_path = URL.createObjectURL(file);
    }
};

const uploadImage = async (file) => {
    const formData = new FormData();
    formData.append('image', file, file.name);
    formData.append('category', 'clients');
    
    try {
       
        const response = await fetch('https://aztecsb.com/backend/web/api/upload/image', {
            method: 'POST',
            // Do NOT stringify or spread FormData; send it directly so the browser sets multipart/form-data with boundary
            headers: (() => {
                const token = localStorage.getItem('authToken');
                return token ? { Authorization: `Bearer ${token}` } : {};
            })(),
         
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


const loadClients = async () => {
    loading.value = true;
    try {
        const response = await ApiService.getAdminClients();
        const data = response.data || response;
        
        // Use raw data directly without sanitization
        clients.value = data;
    } catch (e) {
        clients.value = [];
        console.error('Failed to load clients:', e);
    } finally {
        loading.value = false;
    }
};

const handleImageError = (event) => {
    const img = event.target;
    if (img.__fallbackApplied) return; // prevent infinite loop
    img.__fallbackApplied = true;
    img.src = '/images/placeholder-client.png';
};

const openNew = () => {
    currentClient.value = null;
    selectedFile.value = null;
    resetForm();
    dialogVisible.value = true;
};

const editClient = (client) => {
    currentClient.value = client;
    selectedFile.value = null;
    form.name = client.name || '';
    form.short_name = client.short_name || '';
    form.logo_path = client.image_url || client.logo_path || '';
    dialogVisible.value = true;
};

const save = async () => {
    // Validate form
    if (!form.name || !form.short_name || !form.logo_path) {
        error('Validation Error', 'Please fill in all required fields');
        return;
    }

    saving.value = true;
    try {
        let logoPath = form.logo_path;
        
        // Upload image if a new file was selected
        if (selectedFile.value) {
            logoPath = await uploadImage(selectedFile.value);
        }
        
        const formData = {
            name: form.name,
            short_name: form.short_name,
            logo_path: logoPath
        };
        
        if (currentClient.value?.id) {
            await ApiService.updateClient(currentClient.value.id, formData);
            success('Success', 'Client updated successfully');
        } else {
            await ApiService.createClient(formData);
            success('Success', 'Client created successfully');
        }
        await loadClients();
        dialogVisible.value = false;
    } catch (e) {
        console.error('Failed to save client:', e);
        error('Error', 'Failed to save client');
    } finally {
        saving.value = false;
    }
};

const confirmDelete = (client) => {
    if (deleting.value) return; // Prevent multiple delete operations
    clientToDelete.value = client;
    deleteDialogVisible.value = true;
};

const executeDelete = async () => {
    if (deleting.value || !clientToDelete.value) return;
    
    deleting.value = true;
    
    try {
        await ApiService.deleteClient(clientToDelete.value.id);
        success('Success', 'Client deleted successfully');
        await loadClients();
        deleteDialogVisible.value = false;
        clientToDelete.value = null;
    } catch (e) {
        console.error('Failed to delete client:', e);
        error('Error', 'Failed to delete client');
    } finally {
        deleting.value = false;
    }
};

onMounted(loadClients);
</script>

<style scoped>
.field { 
    margin-bottom: 1rem; 
}

.p-invalid {
    border-color: #e24c4c;
}
</style>
