<template>
    <div>
        <div class="flex justify-content-between align-items-center mb-3">
            <h6 class="m-0">Contact Management</h6>
            <Button class='p-button-primary ml-3' label="Add Contact" icon="pi pi-plus" @click="openNew" />
        </div>

        <DataTable :value="contacts" :loading="loading" dataKey="id" responsiveLayout="scroll" 
                   :paginator="true" :rows="10" paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                   :rowsPerPageOptions="[5,10,25]">
            <Column field="id" header="ID" style="width: 80px" />
            <Column field="office_name" header="Office Name" />
            <Column field="address" header="Address" />
            <Column field="phone" header="Phone" />
            <Column header="Actions" style="width: 160px">
                <template #body="{ data }">
                    <div class="flex gap-2">
                        <Button icon="pi pi-pencil" size="small" @click="editContact(data)" />
                        <Button icon="pi pi-trash" size="small" severity="danger" @click="confirmDelete(data)" />
                    </div>
                </template>
            </Column>
        </DataTable>

        <Dialog v-model:visible="dialogVisible" :modal="true" :header="currentContact?.id ? 'Edit Contact' : 'Add Contact'" 
                :style="{ width: '600px' }" class="p-fluid">
            <div class="field">
                <label class="mr-4" for="office_name">Office Name *</label>
                <InputText id="office_name" v-model="form.office_name" :class="{ 'p-invalid': !form.office_name }" />
                <small v-if="!form.office_name" class="p-error ml-4">Office name is required.</small>
            </div>
            
            <div class="field">
                <label class="mr-4" for="address">Address *</label>
                <Textarea id="address" v-model="form.address" rows="3" 
                         :class="{ 'p-invalid': !form.address }" />
                <small v-if="!form.address" class="p-error ml-4">Address is required.</small>
            </div>
            
            <div class="field">
                <label class="mr-4" for="phone">Phone *</label>
                <InputText id="phone" v-model="form.phone" :class="{ 'p-invalid': !form.phone }" />
                <small v-if="!form.phone" class="p-error ml-4">Phone is required.</small>
                <small class="text-500 ml-4">Enter phone number(s) separated by line breaks if multiple</small>
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
import Textarea from 'primevue/textarea';
import { useConfirm } from 'primevue/useconfirm';
import { onMounted, reactive, ref } from 'vue';

const confirm = useConfirm();

const contacts = ref([]);
const loading = ref(false);
const saving = ref(false);
const dialogVisible = ref(false);
const currentContact = ref(null);
const form = reactive({ 
    office_name: '', 
    address: '', 
    phone: ''
});

const resetForm = () => {
    form.office_name = '';
    form.address = '';
    form.phone = '';
};

const loadContacts = async () => {
    loading.value = true;
    try {
        const response = await ApiService.getContacts();
        contacts.value = response.data || response;
    } catch (e) {
        contacts.value = [];
        console.error('Failed to load contacts:', e);
    } finally {
        loading.value = false;
    }
};

const openNew = () => {
    currentContact.value = null;
    resetForm();
    dialogVisible.value = true;
};

const editContact = (contact) => {
    currentContact.value = contact;
    form.office_name = contact.office_name || '';
    form.address = contact.address || '';
    form.phone = contact.phone || '';
    dialogVisible.value = true;
};

const save = async () => {
    // Validate form
    if (!form.office_name || !form.address || !form.phone) {
        return;
    }

    saving.value = true;
    try {
        if (currentContact.value?.id) {
            await ApiService.updateContact(currentContact.value.id, { ...form });
        } else {
            await ApiService.createContact({ ...form });
        }
        await loadContacts();
        dialogVisible.value = false;
    } catch (e) {
        console.error('Failed to save contact:', e);
    } finally {
        saving.value = false;
    }
};

const confirmDelete = (contact) => {
    confirm.require({
        message: `Are you sure you want to delete "${contact.office_name}"?`,
        header: 'Confirm Delete',
        icon: 'pi pi-exclamation-triangle',
        rejectClass: 'p-button-secondary p-button-outlined',
        rejectLabel: 'Cancel',
        acceptLabel: 'Delete',
        accept: async () => {
            try {
                await ApiService.deleteContact(contact.id);
                await loadContacts();
            } catch (e) {
                console.error('Failed to delete contact:', e);
            }
        }
    });
};

onMounted(loadContacts);
</script>

<style scoped>
.field { 
    margin-bottom: 1rem; 
}

.p-invalid {
    border-color: #e24c4c;
}
</style>
