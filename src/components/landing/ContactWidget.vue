<template>
    <div id="contact" class="py-6 px-6 lg:px-20 my-2 md:my-6 bg-white relative overflow-hidden">
        <!-- Background wavy lines -->
        <div class="absolute top-0 right-0 w-96 h-96 opacity-10">
            <svg viewBox="0 0 400 400" class="w-full h-full">
                <path d="M0,100 Q100,50 200,100 T400,100 L400,0 L0,0 Z" fill="currentColor" class="text-gray-300"/>
                <path d="M0,200 Q100,150 200,200 T400,200 L400,100 L0,100 Z" fill="currentColor" class="text-gray-200"/>
                <path d="M0,300 Q100,250 200,300 T400,300 L400,200 L0,200 Z" fill="currentColor" class="text-gray-300"/>
            </svg>
        </div>

        <div class="relative z-10">
            <!-- Header -->
            <div class="mb-12">
          
                <div class="text-center mb-8">
            
                    <div class="text-surface-900 dark:text-surface-0 font-bold text-3xl mb-4">Contact</div>
                    <div class="h-1 w-16 bg-primary-500 mx-auto"></div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="text-center py-12">
                <div class="text-red-500 text-lg">Failed to load contact information. Please try again later.</div>
            </div>

            <!-- Office Contact Cards -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">
                <div 
                    v-for="contact in contacts" 
                    :key="contact.id"
                    class="bg-white rounded-lg shadow-lg p-6 border border-gray-100"
                >
                    <h3 class="text-surface-900 dark:text-surface-0 font-bold text-xl mb-4">{{ contact.name }}</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="pi pi-map-marker text-red-500 text-lg mr-3 mt-1"></i>
                            <div>
                                <div class="text-red-500 font-semibold text-sm mb-1">FIND US</div>
                                <div class="text-surface-700 dark:text-surface-300 text-sm leading-relaxed">
                                    {{ contact.address }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="pi pi-phone text-red-500 text-lg mr-3 mt-1"></i>
                            <div>
                                <div class="text-red-500 font-semibold text-sm mb-1">CALL US</div>
                                <div class="text-surface-700 dark:text-surface-300 text-sm">
                                    <div v-for="phone in getPhoneNumbers(contact.phone)" :key="phone">{{ phone }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ApiService } from '@/service/ApiService.js';
import { onMounted, ref } from 'vue';

const contacts = ref([]);
const loading = ref(true);
const error = ref(false);

const loadContacts = async () => {
    try {
        loading.value = true;
        error.value = false;
        
        const response = await ApiService.getContacts();
        contacts.value = response.value || response.data || response;
        
        // If no contacts, show empty state
        if (!contacts.value || contacts.value.length === 0) {
            contacts.value = [];
        }
    } catch (err) {
        console.error('Failed to load contacts:', err);
        error.value = true;
        contacts.value = [];
    } finally {
        loading.value = false;
    }
};

const getPhoneNumbers = (phoneString) => {
    if (!phoneString) return [];
    return phoneString.split('\n').filter(phone => phone.trim() !== '');
};

onMounted(() => {
    loadContacts();
});
</script>
