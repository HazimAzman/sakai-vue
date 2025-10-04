<template>
    <div>
        <div class="flex justify-content-between align-items-center mb-3">
            <h6 class="m-0">Institute Management</h6>
            <Button class='p-button-primary ml-3' label="Add Institute" icon="pi pi-plus" @click="openNew" />
        </div>

        <DataTable :value="institutes" :loading="loading" dataKey="id" responsiveLayout="scroll" 
                   :paginator="true" :rows="10" paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                   :rowsPerPageOptions="[5,10,25]">
            <Column field="id" header="ID" style="width: 80px" />
            <Column field="name" header="Institute Name" />
            <Column field="abbreviation" header="Abbreviation" />
            <Column field="image_path" header="Image Path" />
            <Column header="Actions" style="width: 160px">
                <template #body="{ data }">
                    <div class="flex gap-2">
                        <Button icon="pi pi-pencil" size="small" @click="editInstitute(data)" />
                        <Button icon="pi pi-trash" size="small" severity="danger" @click="confirmDelete(data)" />
                    </div>
                </template>
            </Column>
        </DataTable>

        <Dialog v-model:visible="dialogVisible" :modal="true" :header="currentInstitute?.id ? 'Edit Institute' : 'Add Institute'" 
                :style="{ width: '600px' }" class="p-fluid">
            <div class="mb-3 field">
                <label class="mr-4" for="name">Institute Name *</label>
                <InputText id="name" v-model="form.name" :class="{ 'p-invalid': !form.name }" />
                <small v-if="!form.name" class="p-error ml-4">Institute name is required.</small>
            </div>
            
            <div class="mb-3 field">
                <label class="mr-4" for="abbreviation">Abbreviation *</label>
                <InputText id="abbreviation" v-model="form.abbreviation" :class="{ 'p-invalid': !form.abbreviation }" />
                <small v-if="!form.abbreviation" class="p-error ml-4">Abbreviation is required.</small>
            </div>
            
            <div class="mb-3 field">
                <label class="mr-4" for="image_path">Image Path *</label>
                <InputText id="image_path" v-model="form.image_path" :class="{ 'p-invalid': !form.image_path }" />
                <small v-if="!form.image_path" class="p-error ml-4">Image path is required.</small>
                <small class="text-500 ml-4">Enter the path to the institute logo image (e.g., /images/institut/example.png)</small>
            </div>

            <template #footer>
                <Button label="Cancel" class="p-button-text ml-3" icon="pi pi-times" @click="dialogVisible = false" />
                <Button label="Save" class="ml-3" icon="pi pi-check" @click="save" :loading="saving" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteDialogVisible" :modal="true" header="Confirm Delete" :style="{ width: '450px' }">
            <div class="flex align-items-center justify-content-center">
                <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                <span v-if="currentInstitute">Are you sure you want to delete <b>{{ currentInstitute.name }}</b>?</span>
            </div>
            <template #footer>
                <Button label="No" icon="pi pi-times" class="p-button-text" @click="deleteDialogVisible = false" />
                <Button label="Yes" icon="pi pi-check" class="p-button-danger" @click="deleteInstitute" :loading="deleting" />
            </template>
        </Dialog>
    </div>
</template>

<script setup>
import { useNotifications } from '@/composables/useNotifications.js';
import { ApiService } from '@/service/ApiService.js';
import { onMounted, ref } from 'vue';

const { success: showSuccess, error: showError } = useNotifications();

const institutes = ref([]);
const loading = ref(false);
const saving = ref(false);
const deleting = ref(false);
const dialogVisible = ref(false);
const deleteDialogVisible = ref(false);
const currentInstitute = ref(null);

const form = ref({
    name: '',
    abbreviation: '',
    image_path: ''
});

const loadInstitutes = async () => {
    try {
        loading.value = true;
        const response = await ApiService.getInstitutes();
        institutes.value = response.value || response.data || response;
    } catch (error) {
        console.error('Failed to load institutes:', error);
        showError('Error', 'Failed to load institutes');
    } finally {
        loading.value = false;
    }
};

const openNew = () => {
    currentInstitute.value = null;
    form.value = {
        name: '',
        abbreviation: '',
        image_path: ''
    };
    dialogVisible.value = true;
};

const editInstitute = (institute) => {
    currentInstitute.value = institute;
    form.value = {
        name: institute.name,
        abbreviation: institute.abbreviation,
        image_path: institute.image_path
    };
    dialogVisible.value = true;
};

const save = async () => {
    if (!form.value.name || !form.value.abbreviation || !form.value.image_path) {
        showError('Validation Error', 'Please fill in all required fields');
        return;
    }

    try {
        saving.value = true;
        
        if (currentInstitute.value?.id) {
            // Update existing institute
            await ApiService.updateInstitute(currentInstitute.value.id, form.value);
            showSuccess('Success', 'Institute updated successfully');
        } else {
            // Create new institute
            await ApiService.createInstitute(form.value);
            showSuccess('Success', 'Institute created successfully');
        }
        
        dialogVisible.value = false;
        await loadInstitutes();
    } catch (error) {
        console.error('Failed to save institute:', error);
        showError('Error', 'Failed to save institute');
    } finally {
        saving.value = false;
    }
};

const confirmDelete = (institute) => {
    currentInstitute.value = institute;
    deleteDialogVisible.value = true;
};

const deleteInstitute = async () => {
    try {
        deleting.value = true;
        await ApiService.deleteInstitute(currentInstitute.value.id);
        showSuccess('Success', 'Institute deleted successfully');
        deleteDialogVisible.value = false;
        await loadInstitutes();
    } catch (error) {
        console.error('Failed to delete institute:', error);
        showError('Error', 'Failed to delete institute');
    } finally {
        deleting.value = false;
    }
};

onMounted(() => {
    loadInstitutes();
});
</script>
