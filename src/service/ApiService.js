// API Service for communicating with Yii2 backend
// Prefer Vite env base URL when available; default to relative '/api' via dev proxy
const ENV_BASE = (typeof import.meta !== 'undefined' && import.meta.env && import.meta.env.VITE_API_BASE_URL) ? String(import.meta.env.VITE_API_BASE_URL).replace(/\/$/, '') : '';
const API_BASE_URL = ENV_BASE || '';

function authHeaders() {
    try {
        const token = localStorage.getItem('authToken');
        return token ? { Authorization: `Bearer ${token}` } : {};
    } catch (_) {
        return {};
    }
}

export const ApiService = {
    // Generic API call method
    async apiCall(endpoint, options = {}) {
        const url = `${API_BASE_URL}${endpoint}`;
        const method = String(options.method || 'GET').toUpperCase();
        const hasBody = options.body !== undefined && options.body !== null;
        const defaultOptions = {
            headers: {
                'Accept': 'application/json',
                ...authHeaders(),
                ...(hasBody ? { 'Content-Type': 'application/json' } : {})
            },
        };

        const config = {
            ...defaultOptions,
            ...options,
            headers: {
                ...defaultOptions.headers,
                ...options.headers,
            },
        };

        try {
            const response = await fetch(url, config);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('API call failed:', error);
            throw error;
        }
    },

    // Section CRUD (generic by section key)
    async listSection(section) {
        return this.apiCall(`/api/sections/${section}`);
    },

    async createSection(section, payload) {
        return this.apiCall(`/api/sections/${section}/create`, {
            method: 'POST',
            body: JSON.stringify(payload),
        });
    },

    async updateSection(section, id, payload) {
        return this.apiCall(`/api/sections/${section}/${id}/update`, {
            method: 'PUT',
            body: JSON.stringify(payload),
        });
    },

    async deleteSection(section, id) {
        return this.apiCall(`/api/sections/${section}/${id}/delete`, {
            method: 'DELETE',
        });
    },

    // Health check
    async healthCheck() {
        return this.apiCall('/api/health');
    },

    // Auth
    async logout() {
        // Send both Authorization header and token in body as fallback
        const token = (typeof localStorage !== 'undefined') ? localStorage.getItem('authToken') : '';
        return this.apiCall('/api/auth/logout', {
            method: 'POST',
            body: JSON.stringify({ token }),
        });
    },

    // Products API
    async getProducts() {
        return this.apiCall('/api/products');
    },

    async getProduct(id) {
        return this.apiCall(`/api/products/${id}`);
    },

    async createProduct(productData) {
        return this.apiCall('/api/products/create', {
            method: 'POST',
            body: JSON.stringify(productData),
        });
    },

    async updateProduct(id, productData) {
        return this.apiCall(`/api/products/${id}/update`, {
            method: 'PUT',
            body: JSON.stringify(productData),
        });
    },

    async deleteProduct(id) {
        return this.apiCall(`/api/products/${id}/delete`, {
            method: 'DELETE',
        });
    },

    // Services API
    async getServices() {
        return this.apiCall('/api/services');
    },

    async getService(id) {
        return this.apiCall(`/api/services/${id}`);
    },

    async createService(serviceData) {
        return this.apiCall('/api/services/create', {
            method: 'POST',
            body: JSON.stringify(serviceData),
        });
    },

    async updateService(id, serviceData) {
        return this.apiCall(`/api/services/${id}/update`, {
            method: 'PUT',
            body: JSON.stringify(serviceData),
        });
    },

    async deleteService(id) {
        return this.apiCall(`/api/services/${id}/delete`, {
            method: 'DELETE',
        });
    },

    // About API
    async getAbout() {
        return this.apiCall('/api/about');
    },

    async getAboutEntry(id) {
        return this.apiCall(`/api/about/${id}`);
    },

    async createAbout(aboutData) {
        return this.apiCall('/api/about/create', {
            method: 'POST',
            body: JSON.stringify(aboutData),
        });
    },

    async updateAbout(id, aboutData) {
        return this.apiCall(`/api/about/${id}/update`, {
            method: 'PUT',
            body: JSON.stringify(aboutData),
        });
    },

    async deleteAbout(id) {
        return this.apiCall(`/api/about/${id}/delete`, {
            method: 'DELETE',
        });
    },

    // Downloads API
    async getDownloads() {
        return this.apiCall('/api/downloads');
    },

    async getDownload(id) {
        return this.apiCall(`/api/downloads/${id}`);
    },

    async createDownload(downloadData) {
        return this.apiCall('/api/downloads/create', {
            method: 'POST',
            body: JSON.stringify(downloadData),
        });
    },

    async updateDownload(id, downloadData) {
        return this.apiCall(`/api/downloads/${id}/update`, {
            method: 'PUT',
            body: JSON.stringify(downloadData),
        });
    },

    async deleteDownload(id) {
        return this.apiCall(`/api/downloads/${id}/delete`, {
            method: 'DELETE',
        });
    },

    // Clients API
    async getClients() {
        return this.apiCall('/api/clients');
    },

    async getClient(id) {
        return this.apiCall(`/api/clients/${id}`);
    },

    async createClient(clientData) {
        return this.apiCall('/api/clients/create', {
            method: 'POST',
            body: JSON.stringify(clientData),
        });
    },

    async updateClient(id, clientData) {
        return this.apiCall(`/api/clients/${id}/update`, {
            method: 'PUT',
            body: JSON.stringify(clientData),
        });
    },

    async deleteClient(id) {
        return this.apiCall(`/api/clients/${id}/delete`, {
            method: 'DELETE',
        });
    },

    // Contacts API
    async getContacts() {
        return this.apiCall('/api/contacts');
    },

    async getContact(id) {
        return this.apiCall(`/api/contacts/${id}`);
    },

    async createContact(contactData) {
        return this.apiCall('/api/contacts/create', {
            method: 'POST',
            body: JSON.stringify(contactData),
        });
    },

    async updateContact(id, contactData) {
        return this.apiCall(`/api/contacts/${id}/update`, {
            method: 'PUT',
            body: JSON.stringify(contactData),
        });
    },

    async deleteContact(id) {
        return this.apiCall(`/api/contacts/${id}/delete`, {
            method: 'DELETE',
        });
    },

    // Institutes API
    async getInstitutes() {
        return this.apiCall('/api/institutes');
    },

    async getInstitute(id) {
        return this.apiCall(`/api/institutes/${id}`);
    },

    async createInstitute(instituteData) {
        return this.apiCall('/api/institutes/create', {
            method: 'POST',
            body: JSON.stringify(instituteData),
        });
    },

    async updateInstitute(id, instituteData) {
        return this.apiCall(`/api/institutes/${id}/update`, {
            method: 'PUT',
            body: JSON.stringify(instituteData),
        });
    },

    async deleteInstitute(id) {
        return this.apiCall(`/api/institutes/${id}/delete`, {
            method: 'DELETE',
        });
    },

    // Activities API
    async getActivities() {
        return this.apiCall('/api/activities');
    },

    async getActivity(id) {
        return this.apiCall(`/api/activities/${id}`);
    },

    async createActivity(activityData) {
        return this.apiCall('/api/activities/create', {
            method: 'POST',
            body: JSON.stringify(activityData),
        });
    },

    async updateActivity(id, activityData) {
        return this.apiCall(`/api/activities/${id}/update`, {
            method: 'PUT',
            body: JSON.stringify(activityData),
        });
    },

    async deleteActivity(id) {
        return this.apiCall(`/api/activities/${id}/delete`, {
            method: 'DELETE',
        });
    }
};
