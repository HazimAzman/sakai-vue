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
                    <img v-if="data.logo_path" :src="data.logo_path" :alt="data.name" 
                         class="w-4rem h-4rem object-cover border-round" />
                    <span v-else class="text-500">No logo</span>
                </template>
            </Column>
            <Column header="Actions" style="width: 160px">
                <template #body="{ data }">
                    <div class="flex gap-2">
                        <Button icon="pi pi-pencil" size="small" @click="editClient(data)" />
                        <Button icon="pi pi-trash" size="small" severity="danger" @click="confirmDelete(data)" />
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
                <label class="mr-4" for="logo_path">Logo Path *</label>
                <InputText id="logo_path" v-model="form.logo_path" 
                          placeholder="/images/universiti/example.png"
                          :class="{ 'p-invalid': !form.logo_path }" />
                <small v-if="!form.logo_path" class="p-error ml-4">Logo path is required.</small>
                <small class="text-500 ml-4">Enter the path to the client logo (e.g., /images/universiti/umk.png)</small>
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
import { ApiService } from '@/service/ApiService.js';
import Button from 'primevue/button';
import Column from 'primevue/column';
import ConfirmDialog from 'primevue/confirmdialog';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import { useConfirm } from 'primevue/useconfirm';
import { onMounted, reactive, ref } from 'vue';

const confirm = useConfirm();

const clients = ref([]);
const loading = ref(false);
const saving = ref(false);
const dialogVisible = ref(false);
const currentClient = ref(null);
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

const loadClients = async () => {
    loading.value = true;
    try {
        const response = await ApiService.getClients();
        clients.value = response.data || response;
    } catch (e) {
        clients.value = [];
        console.error('Failed to load clients:', e);
    } finally {
        loading.value = false;
    }
};

const openNew = () => {
    currentClient.value = null;
    resetForm();
    dialogVisible.value = true;
};

const editClient = (client) => {
    currentClient.value = client;
    form.name = client.name || '';
    form.short_name = client.short_name || '';
    form.logo_path = client.logo_path || '';
    dialogVisible.value = true;
};

const save = async () => {
    // Validate form
    if (!form.name || !form.short_name || !form.logo_path) {
        return;
    }

    saving.value = true;
    try {
        if (currentClient.value?.id) {
            await ApiService.updateClient(currentClient.value.id, { ...form });
        } else {
            await ApiService.createClient({ ...form });
        }
        await loadClients();
        dialogVisible.value = false;
    } catch (e) {
        console.error('Failed to save client:', e);
    } finally {
        saving.value = false;
    }
};

const confirmDelete = (client) => {
    confirm.require({
        message: `Are you sure you want to delete "${client.name}"?`,
        header: 'Confirm Delete',
        icon: 'pi pi-exclamation-triangle',
        rejectClass: 'p-button-secondary p-button-outlined',
        rejectLabel: 'Cancel',
        acceptLabel: 'Delete',
        accept: async () => {
            try {
                await ApiService.deleteClient(client.id);
                await loadClients();
            } catch (e) {
                console.error('Failed to delete client:', e);
            }
        }
    });
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
