<template>
    <div>
        <div class="flex justify-content-between align-items-center mb-3">
            <h6 class="m-0">Download Management</h6>
            <Button class="ml-3" label="Add Download" icon="pi pi-plus" @click="openNew" />
        </div>

        <DataTable :value="downloads" :loading="loading" dataKey="id" responsiveLayout="scroll" 
                   :paginator="true" :rows="10" paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                   :rowsPerPageOptions="[5,10,25]">
            <Column field="id" header="ID" style="width: 80px" />
            <Column field="brand_name" header="Brand Name" />
            <Column field="download_url" header="Download URL" />
            <Column header="Actions" style="width: 160px">
                <template #body="{ data }">
                    <div class="flex gap-2">
                        <Button icon="pi pi-pencil" size="small" @click="editDownload(data)" />
                        <Button icon="pi pi-trash" size="small" severity="danger" 
                                :loading="deleting" :disabled="deleting" 
                                @click="confirmDelete(data)" />
                    </div>
                </template>
            </Column>
        </DataTable>

        <Dialog v-model:visible="dialogVisible" :modal="true" :header="currentDownload?.id ? 'Edit Download' : 'Add Download'" 
                :style="{ width: '600px' }" class="p-fluid">
            <div class="field">
                <label class="mr-3" for="brand_name">Brand Name *</label>
                <InputText id="brand_name" v-model="form.brand_name" :class="{ 'p-invalid': !form.brand_name }" />
                <small v-if="!form.brand_name" class="ml-3 p-error">Brand name is required.</small>
            </div>
            
            <div class="field">
                <label class="mr-3" for="download_url">Download URL *</label>
                <InputText id="download_url" v-model="form.download_url" 
                          placeholder="https://example.com/download"
                          :class="{ 'p-invalid': !form.download_url }" />
                <small v-if="!form.download_url" class="ml-3 p-error">Download URL is required.</small>
                <small class="ml-3 text-500">Enter the full URL for the download</small>
            </div>

            <template #footer>
                <Button label="Cancel" class="p-button-text ml-3" icon="pi pi-times" @click="dialogVisible = false" />
                <Button label="Save" class="ml-3" icon="pi pi-check" @click="save" :loading="saving" />
            </template>
        </Dialog>

        <!-- Custom Delete Confirmation Dialog -->
        <Dialog v-model:visible="deleteDialogVisible" :modal="true" header="Confirm Delete" 
                :style="{ width: '400px' }" class="p-fluid">
            <div class="flex align-items-center">
                <i class="pi pi-exclamation-triangle text-orange-500 text-2xl mr-3"></i>
                <span>Are you sure you want to delete "{{ downloadToDelete?.brand_name }}"?</span>
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
import InputText from 'primevue/inputtext';
import { onMounted, reactive, ref } from 'vue';

const { success, error } = useNotifications();

const downloads = ref([]);
const loading = ref(false);
const saving = ref(false);
const deleting = ref(false);
const dialogVisible = ref(false);
const deleteDialogVisible = ref(false);
const currentDownload = ref(null);
const downloadToDelete = ref(null);
const form = reactive({ 
    brand_name: '', 
    download_url: ''
});

const resetForm = () => {
    form.brand_name = '';
    form.download_url = '';
};


const loadDownloads = async () => {
    loading.value = true;
    try {
        const response = await ApiService.getAdminDownloads();
        const data = response.data || response;
        
        // Use raw data directly without sanitization
        downloads.value = data;
    } catch (e) {
        downloads.value = [];
        console.error('Failed to load downloads:', e);
    } finally {
        loading.value = false;
    }
};

const openNew = () => {
    currentDownload.value = null;
    resetForm();
    dialogVisible.value = true;
};

const editDownload = (download) => {
    currentDownload.value = download;
    form.brand_name = download.brand_name || '';
    form.download_url = download.download_url || '';
    dialogVisible.value = true;
};

const save = async () => {
    // Validate form
    if (!form.brand_name || !form.download_url) {
        error('Validation Error', 'Please fill in all required fields');
        return;
    }

    saving.value = true;
    try {
        if (currentDownload.value?.id) {
            await ApiService.updateDownload(currentDownload.value.id, { ...form });
            success('Success', 'Download updated successfully');
        } else {
            await ApiService.createDownload({ ...form });
            success('Success', 'Download created successfully');
        }
        await loadDownloads();
        dialogVisible.value = false;
    } catch (e) {
        console.error('Failed to save download:', e);
        error('Error', 'Failed to save download');
    } finally {
        saving.value = false;
    }
};

const confirmDelete = (download) => {
    if (deleting.value) return; // Prevent multiple delete operations
    downloadToDelete.value = download;
    deleteDialogVisible.value = true;
};

const executeDelete = async () => {
    if (deleting.value || !downloadToDelete.value) return;
    
    deleting.value = true;
    
    try {
        await ApiService.deleteDownload(downloadToDelete.value.id);
        success('Success', 'Download deleted successfully');
        await loadDownloads();
        deleteDialogVisible.value = false;
        downloadToDelete.value = null;
    } catch (e) {
        console.error('Failed to delete download:', e);
        error('Error', 'Failed to delete download');
    } finally {
        deleting.value = false;
    }
};

onMounted(loadDownloads);
</script>

<style scoped>
.field { 
    margin-bottom: 1rem; 
}

.p-invalid {
    border-color: #e24c4c;
}
</style>
