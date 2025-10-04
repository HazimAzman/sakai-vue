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
                        <Button icon="pi pi-trash" size="small" severity="danger" @click="confirmDelete(data)" />
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
                <label class="mr-3" for="image_path">Image Path *</label>
                <InputText id="image_path" v-model="form.image_path" 
                          placeholder="/images/service/example.png"
                          :class="{ 'p-invalid': !form.image_path }" />
                <small v-if="!form.image_path" class="ml-3 p-error">Image path is required.</small>
                <small class="ml-3 text-500">Enter the path to the service image (e.g., /images/service/lab-scientific-equipments-supply.png)</small>
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
import { useNotifications } from '@/composables/useNotifications.js';
import { ApiService } from '@/service/ApiService.js';
import Button from 'primevue/button';
import Column from 'primevue/column';
import ConfirmDialog from 'primevue/confirmdialog';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import { useConfirm } from 'primevue/useconfirm';
import { onMounted, reactive, ref } from 'vue';

const confirm = useConfirm();
const { success, error } = useNotifications();

const services = ref([]);
const loading = ref(false);
const saving = ref(false);
const dialogVisible = ref(false);
const currentService = ref(null);
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
    resetForm();
    dialogVisible.value = true;
};

const editService = (service) => {
    currentService.value = service;
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
        if (currentService.value?.id) {
            await ApiService.updateService(currentService.value.id, { ...form });
            success('Success', 'Service updated successfully');
        } else {
            await ApiService.createService({ ...form });
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
    confirm.require({
        message: `Are you sure you want to delete "${service.title}"?`,
        header: 'Confirm Delete',
        icon: 'pi pi-exclamation-triangle',
        rejectClass: 'p-button-secondary p-button-outlined',
        rejectLabel: 'Cancel',
        acceptLabel: 'Delete',
        accept: async () => {
            try {
                await ApiService.deleteService(service.id);
                success('Success', 'Service deleted successfully');
                await loadServices();
            } catch (e) {
                console.error('Failed to delete service:', e);
                error('Error', 'Failed to delete service');
            }
        }
    });
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
