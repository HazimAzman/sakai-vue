<template>
    <div>
        <div class="flex justify-content-between align-items-center mb-3">
            <h6 class="m-0">About Management</h6>
            <Button class='p-button-primary ml-3' label="Add About Entry" icon="pi pi-plus" @click="openNew" />
        </div>

        <DataTable :value="aboutEntries" :loading="loading" dataKey="id" responsiveLayout="scroll" 
                   :paginator="true" :rows="10" paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                   :rowsPerPageOptions="[5,10,25]">
            <Column field="id" header="ID" style="width: 80px" />
            <Column field="title" header="Title" />
            <Column field="ceo_name" header="CEO Name" />
            <Column field="ceo_title" header="CEO Title" />
            <Column header="CEO Image" style="width: 100px">
                <template #body="{ data }">
                    <img v-if="data.ceo_image" :src="data.ceo_image" :alt="data.ceo_name" 
                         class="w-4rem h-4rem object-cover border-round" />
                    <span v-else class="text-500">No image</span>
                </template>
            </Column>
            <Column header="Actions" style="width: 160px">
                <template #body="{ data }">
                    <div class="flex gap-2">
                        <Button icon="pi pi-pencil" size="small" @click="editAbout(data)" />
                        <Button icon="pi pi-trash" size="small" severity="danger" 
                                :loading="deleting" :disabled="deleting" 
                                @click="confirmDelete(data)" />
                    </div>
                </template>
            </Column>
        </DataTable>

        <Dialog v-model:visible="dialogVisible" :modal="true" :header="currentAbout?.id ? 'Edit About Entry' : 'Add About Entry'" 
                :style="{ width: '700px' }" class="p-fluid">
            <div class="field">
                <label class="mr-4" for="title">Title *</label>
                <InputText id="title" v-model="form.title" :class="{ 'p-invalid': !form.title }" />
                <small v-if="!form.title" class="p-error ml-4">Title is required.</small>
            </div>
            
            <div class="field">
                <label class="mr-4" for="content">Content *</label>
                <Textarea id="content" v-model="form.content" rows="6" 
                         :class="{ 'p-invalid': !form.content }" />
                <small v-if="!form.content" class="p-error ml-4">Content is required.</small>
            </div>
            
            <div class="field">
                <label class="mr-4" for="ceo_name">CEO Name *</label>
                <InputText id="ceo_name" v-model="form.ceo_name" :class="{ 'p-invalid': !form.ceo_name }" />
                <small v-if="!form.ceo_name" class="p-error ml-4">CEO Name is required.</small>
            </div>
            
            <div class="field">
                <label class="mr-4" for="ceo_title">CEO Title *</label>
                <InputText id="ceo_title" v-model="form.ceo_title" :class="{ 'p-invalid': !form.ceo_title }" />
                <small v-if="!form.ceo_title" class="p-error ml-4">CEO Title is required.</small>
            </div>
            
            <div class="field">
                <label class="mr-4" for="ceo_image">CEO Image *</label>
                <FileUpload id="ceo_image" 
                           mode="basic" 
                           name="image" 
                           accept="image/*" 
                           :maxFileSize="5000000"
                           @select="onImageSelect"
                           :auto="true"
                           chooseLabel="Choose CEO Image"
                           :class="{ 'p-invalid': !form.ceo_image }" />
                <small v-if="!form.ceo_image" class="p-error ml-4">CEO Image is required.</small>
                <small class="text-500 ml-4">Upload an image for the CEO (max 5MB)</small>
                
                <!-- Preview uploaded image -->
                <div v-if="form.ceo_image" class="mt-3">
                    <img :src="form.ceo_image" :alt="form.ceo_name" 
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
                <span>Are you sure you want to delete "{{ aboutToDelete?.title }}"?</span>
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

const aboutEntries = ref([]);
const loading = ref(false);
const saving = ref(false);
const deleting = ref(false);
const dialogVisible = ref(false);
const deleteDialogVisible = ref(false);
const currentAbout = ref(null);
const aboutToDelete = ref(null);
const selectedFile = ref(null);
const form = reactive({ 
    title: '', 
    content: '', 
    ceo_name: '', 
    ceo_title: '', 
    ceo_image: ''
});

const resetForm = () => {
    form.title = '';
    form.content = '';
    form.ceo_name = '';
    form.ceo_title = '';
    form.ceo_image = '';
};

const onImageSelect = (event) => {
    const file = event.files[0];
    if (file) {
        selectedFile.value = file;
        form.ceo_image = URL.createObjectURL(file);
    }
};

const uploadImage = async (file) => {
    const formData = new FormData();
    formData.append('image', file);
    formData.append('category', 'about');
    
    try {
        const response = await fetch('/api/upload/image', {
            method: 'POST',
            body: formData
        });
        
        if (!response.ok) {
            throw new Error('Failed to upload image');
        }
        
        const result = await response.json();
        return result.path || result.url;
    } catch (error) {
        console.error('Image upload failed:', error);
        throw error;
    }
};

const loadAbout = async () => {
    loading.value = true;
    try {
        const response = await ApiService.getAbout();
        aboutEntries.value = response.data || response;
    } catch (e) {
        aboutEntries.value = [];
        console.error('Failed to load about entries:', e);
    } finally {
        loading.value = false;
    }
};

const openNew = () => {
    currentAbout.value = null;
    selectedFile.value = null;
    resetForm();
    dialogVisible.value = true;
};

const editAbout = (about) => {
    currentAbout.value = about;
    selectedFile.value = null;
    form.title = about.title || '';
    form.content = about.content || '';
    form.ceo_name = about.ceo_name || '';
    form.ceo_title = about.ceo_title || '';
    form.ceo_image = about.ceo_image || '';
    dialogVisible.value = true;
};

const save = async () => {
    // Validate form
    if (!form.title || !form.content || !form.ceo_name || !form.ceo_title || !form.ceo_image) {
        error('Validation Error', 'Please fill in all required fields');
        return;
    }

    saving.value = true;
    try {
        let imagePath = form.ceo_image;
        
        // Upload image if a new file was selected
        if (selectedFile.value) {
            imagePath = await uploadImage(selectedFile.value);
        }
        
        const formData = {
            title: form.title,
            content: form.content,
            ceo_name: form.ceo_name,
            ceo_title: form.ceo_title,
            ceo_image: imagePath
        };
        
        if (currentAbout.value?.id) {
            await ApiService.updateAbout(currentAbout.value.id, formData);
            success('Success', 'About entry updated successfully');
        } else {
            await ApiService.createAbout(formData);
            success('Success', 'About entry created successfully');
        }
        await loadAbout();
        dialogVisible.value = false;
    } catch (e) {
        console.error('Failed to save about entry:', e);
        error('Error', 'Failed to save about entry');
    } finally {
        saving.value = false;
    }
};

const confirmDelete = (about) => {
    if (deleting.value) return; // Prevent multiple delete operations
    aboutToDelete.value = about;
    deleteDialogVisible.value = true;
};

const executeDelete = async () => {
    if (deleting.value || !aboutToDelete.value) return;
    
    deleting.value = true;
    
    try {
        await ApiService.deleteAbout(aboutToDelete.value.id);
        success('Success', 'About entry deleted successfully');
        await loadAbout();
        deleteDialogVisible.value = false;
        aboutToDelete.value = null;
    } catch (e) {
        console.error('Failed to delete about entry:', e);
        error('Error', 'Failed to delete about entry');
    } finally {
        deleting.value = false;
    }
};

onMounted(loadAbout);
</script>

<style scoped>
.field { 
    margin-bottom: 1rem; 
}

.p-invalid {
    border-color: #e24c4c;
}
</style>
