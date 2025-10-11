<template>
    <div>
        <div class="flex justify-content-between align-items-center mb-3">
            <h6 class="m-0">Activity Management</h6>
            <Button class='p-button-primary ml-3' label="Add Activity" icon="pi pi-plus" @click="openNew" />
        </div>

        <DataTable :value="activities" :loading="loading" dataKey="id" responsiveLayout="scroll" 
                   :paginator="true" :rows="10" paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                   :rowsPerPageOptions="[5,10,25]">
            <Column field="id" header="ID" style="width: 80px" />
            <Column field="title" header="Title" />
            <Column header="Image" style="width: 100px">
                <template #body="{ data }">
                    <img v-if="data.image_path" :src="data.image_path" :alt="data.title" 
                         class="w-4rem h-4rem object-cover border-round" />
                    <span v-else class="text-500">No image</span>
                </template>
            </Column>
            <Column field="description" header="Description" style="max-width: 300px">
                <template #body="{ data }">
                    <div class="text-overflow-ellipsis" :title="data.description">
                        {{ data.description.substring(0, 100) }}{{ data.description.length > 100 ? '...' : '' }}
                    </div>
                </template>
            </Column>
            <Column header="Actions" style="width: 160px">
                <template #body="{ data }">
                    <div class="flex gap-2">
                        <Button icon="pi pi-pencil" size="small" @click="editActivity(data)" />
                        <Button icon="pi pi-trash" size="small" severity="danger" @click="confirmDelete(data)" />
                    </div>
                </template>
            </Column>
        </DataTable>

        <Dialog v-model:visible="dialogVisible" :modal="true" :header="currentActivity?.id ? 'Edit Activity' : 'Add Activity'" 
                :style="{ width: '700px' }" class="p-fluid">
            <div class="field mb-3">
                <label class="mr-4" for="title">Title *</label>
                <InputText id="title" v-model="form.title" :class="{ 'p-invalid': !form.title }" />
                <small v-if="!form.title" class="p-error ml-4">Title is required.</small>
            </div>
            
            <div class="field mb-3">
                <label class="mr-4" for="description">Description *</label>
                <Textarea id="description" v-model="form.description" rows="4" 
                         :class="{ 'p-invalid': !form.description }" />
                <small v-if="!form.description" class="p-error ml-4">Description is required.</small>
            </div>
            
            <div class="field mb-3">
                <label class="mr-4" for="image">Activity Image *</label>
                <FileUpload id="image" 
                           mode="basic" 
                           name="image" 
                           accept="image/*" 
                           :maxFileSize="5000000"
                           @select="onImageSelect"
                           :auto="true"
                           chooseLabel="Choose Image"
                           :class="{ 'p-invalid': !form.image_path }" />
                <small v-if="!form.image_path" class="p-error ml-4">Image is required.</small>
                <small class="text-500 ml-4">Upload an image for this activity (max 5MB)</small>
                
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

        <Dialog v-model:visible="deleteDialogVisible" :modal="true" header="Confirm Delete" :style="{ width: '450px' }">
            <div class="flex align-items-center justify-content-center">
                <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                <span v-if="currentActivity">Are you sure you want to delete <b>{{ currentActivity.title }}</b>?</span>
            </div>
            <template #footer>
                <Button label="No" icon="pi pi-times" class="p-button-text" @click="deleteDialogVisible = false" />
                <Button label="Yes" icon="pi pi-check" class="p-button-danger" @click="deleteActivity" :loading="deleting" />
            </template>
        </Dialog>
    </div>
</template>

<script setup>
import { useNotifications } from '@/composables/useNotifications.js';
import { ApiService } from '@/service/ApiService.js';
import FileUpload from 'primevue/fileupload';
import { onMounted, ref } from 'vue';

const { success: showSuccess, error: showError } = useNotifications();

const activities = ref([]);
const loading = ref(false);
const saving = ref(false);
const deleting = ref(false);
const dialogVisible = ref(false);
const deleteDialogVisible = ref(false);
const currentActivity = ref(null);

const form = ref({
    title: '',
    description: '',
    image_path: ''
});

const selectedFile = ref(null);

const onImageSelect = (event) => {
    const file = event.files[0];
    if (file) {
        selectedFile.value = file;
        // Create a preview URL for the selected image
        form.value.image_path = URL.createObjectURL(file);
    }
};

const loadActivities = async () => {
    try {
        loading.value = true;
        const response = await ApiService.getActivities();
        activities.value = response.value || response.data || response;
    } catch (error) {
        console.error('Failed to load activities:', error);
        showError('Error', 'Failed to load activities');
    } finally {
        loading.value = false;
    }
};

const openNew = () => {
    currentActivity.value = null;
    selectedFile.value = null;
    form.value = {
        title: '',
        description: '',
        image_path: ''
    };
    dialogVisible.value = true;
};

const editActivity = (activity) => {
    currentActivity.value = activity;
    selectedFile.value = null;
    form.value = {
        title: activity.title,
        description: activity.description,
        image_path: activity.image_path
    };
    dialogVisible.value = true;
};

const uploadImage = async (file) => {
    const formData = new FormData();
    formData.append('image', file);
    
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

const save = async () => {
    if (!form.value.title || !form.value.description || !form.value.image_path) {
        showError('Validation Error', 'Please fill in all required fields');
        return;
    }

    try {
        saving.value = true;
        
        let imagePath = form.value.image_path;
        
        // If a new file was selected, upload it first
        if (selectedFile.value) {
            imagePath = await uploadImage(selectedFile.value);
        }
        
        const activityData = {
            title: form.value.title,
            description: form.value.description,
            image_path: imagePath
        };
        
        if (currentActivity.value?.id) {
            // Update existing activity
            await ApiService.updateActivity(currentActivity.value.id, activityData);
            showSuccess('Success', 'Activity updated successfully');
        } else {
            // Create new activity
            await ApiService.createActivity(activityData);
            showSuccess('Success', 'Activity created successfully');
        }
        
        dialogVisible.value = false;
        await loadActivities();
    } catch (error) {
        console.error('Failed to save activity:', error);
        showError('Error', 'Failed to save activity');
    } finally {
        saving.value = false;
    }
};

const confirmDelete = (activity) => {
    currentActivity.value = activity;
    deleteDialogVisible.value = true;
};

const deleteActivity = async () => {
    try {
        deleting.value = true;
        await ApiService.deleteActivity(currentActivity.value.id);
        showSuccess('Success', 'Activity deleted successfully');
        deleteDialogVisible.value = false;
        await loadActivities();
    } catch (error) {
        console.error('Failed to delete activity:', error);
        showError('Error', 'Failed to delete activity');
    } finally {
        deleting.value = false;
    }
};

onMounted(() => {
    loadActivities();
});
</script>
