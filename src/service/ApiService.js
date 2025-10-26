// API Service for communicating with Yii2 backend
// Prefer Vite env base URL when available; default to relative '/api' via dev proxy
const ENV_BASE = (typeof import.meta !== 'undefined' && import.meta.env && import.meta.env.VITE_API_BASE_URL) ? String(import.meta.env.VITE_API_BASE_URL).replace(/\/$/, '') : '';
const API_BASE_URL = 'https://aztecsb.com/backend/web' || '';



export const ApiService = {
    // Generic API call method
    async apiCall(endpoint, options = {}) {
        const url = `${API_BASE_URL}${endpoint}`;
        const method = String(options.method || 'GET').toUpperCase();
        const hasBody = options.body !== undefined && options.body !== null;
        const token = localStorage.getItem('authToken');
        const defaultOptions = {
            headers: {
                'Accept': 'application/json',
                
                'Authorization': `Bearer ${token}`,
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
                // If token is invalid/expired (401), clear auth state
                if (response.status === 401) {
                    localStorage.removeItem('authToken');
                    localStorage.removeItem('adminLoggedIn');
                    localStorage.removeItem('adminUser');
                    localStorage.removeItem('authUser');
                    // Redirect to login if we're not already there
                    if (window.location.pathname !== '/auth/login') {
                        window.location.href = '/auth/login';
                    }
                }
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
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/sections/${section}/create`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify(payload),
        });
    },

    async updateSection(section, id, payload) {
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/sections/${section}/${id}/update`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify(payload),
        });
    },

    async deleteSection(section, id) {
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/sections/${section}/${id}/delete`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
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

    // Users API (Admin only)


    // Products API
    async getProducts() {
        return this.apiCall('/api/products');
    },

    // Admin Products API (with ID field)
    async getAdminProducts() {
        const token = localStorage.getItem('authToken');
        return this.apiCall('/api/admin/products', {
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        });
    },

    async getProduct(id) {
        return this.apiCall(`/api/products/${id}`);
    },

    async createProduct(productData) {
        const token = localStorage.getItem('authToken');
        return this.apiCall('/api/products/create', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ ...productData})
        });
    },

    async updateProduct(id, productData) {
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/products/${id}/update`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ ...productData})
        });
    },

    async deleteProduct(id) {
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/products/${id}/delete`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        });
    },

    // Services API
    async getServices() {
        return this.apiCall('/api/services');
    },

    // Admin Services API (with ID field)
    async getAdminServices() {
        const token = localStorage.getItem('authToken');
        return this.apiCall('/api/admin/services', {
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        });
    },

    async getService(id) {
        return this.apiCall(`/api/services/${id}`);
    },

    async createService(serviceData) {
        const token = localStorage.getItem('authToken');
        return this.apiCall('/api/services/create', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ ...serviceData})
        });
    },

    async updateService(id, serviceData) {
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/services/${id}/update`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ ...serviceData})
        });
    },

    async deleteService(id) {
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/services/${id}/delete`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        });
    },

    // About API
    async getAbout() {
        return this.apiCall('/api/about');
    },

    // Admin About API (with ID field)
    async getAdminAbout() {
        const token = localStorage.getItem('authToken');
        return this.apiCall('/api/admin/about', {
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        });
    },

    async getAboutEntry(id) {
        return this.apiCall(`/api/about/${id}`);
    },

    async createAbout(aboutData) {
        const token = localStorage.getItem('authToken');
        return this.apiCall('/api/about/create', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ ...aboutData})
        });
    },

    async updateAbout(id, aboutData) {
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/about/${id}/update`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ ...aboutData})
        });
    },

    async deleteAbout(id) {
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/about/${id}/delete`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        });
    },

    // Downloads API
    async getDownloads() {
        return this.apiCall('/api/downloads');
    },

    // Admin Downloads API (with ID field)
    async getAdminDownloads() {
        const token = localStorage.getItem('authToken');
        return this.apiCall('/api/admin/downloads', {
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        });
    },

    async getDownload(id) {
        return this.apiCall(`/api/downloads/${id}`);
    },

    async createDownload(downloadData) {
        const token = localStorage.getItem('authToken');
        return this.apiCall('/api/downloads/create', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ ...downloadData})
        });
    },

    async updateDownload(id, downloadData) {
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/downloads/${id}/update`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ ...downloadData})
        });
    },

    async deleteDownload(id) {
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/downloads/${id}/delete`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        });
    },

    // Clients API
    async getClients() {
        return this.apiCall('/api/clients');
    },

    // Admin Clients API (with ID field)
    async getAdminClients() {
        const token = localStorage.getItem('authToken');
        return this.apiCall('/api/admin/clients', {
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        });
    },

    async getClient(id) {
        return this.apiCall(`/api/clients/${id}`);
    },

    async createClient(clientData) {
        const token = localStorage.getItem('authToken');
        return this.apiCall('/api/clients/create', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ ...clientData})
        });
    },

    async updateClient(id, clientData) {
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/clients/${id}/update`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ ...clientData})
        });
    },

    async deleteClient(id) {
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/clients/${id}/delete`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        });
    },

    // Contacts API
    async getContacts() {
        return this.apiCall('/api/contacts');
    },

    // Admin Contacts API (with ID field)
    async getAdminContacts() {
        return this.apiCall('/api/admin/contacts');
    },

    async getContact(id) {
        return this.apiCall(`/api/contacts/${id}`);
    },

    async createContact(contactData) {
        const token = localStorage.getItem('authToken');
        return this.apiCall('/api/contacts/create', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ ...contactData})
        });
    },

    async updateContact(id, contactData) {
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/contacts/${id}/update`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ ...contactData})
        });
    },

    async deleteContact(id) {
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/contacts/${id}/delete`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        });
    },

    // Institutes API
    async getInstitutes() {
        return this.apiCall('/api/institutes');
    },

    // Admin Institutes API (with ID field)
    async getAdminInstitutes() {
        return this.apiCall('/api/admin/institutes');
    },

    async getInstitute(id) {
        return this.apiCall(`/api/institutes/${id}`);
    },

    async createInstitute(instituteData) {
        const token = localStorage.getItem('authToken');
        return this.apiCall('/api/institutes/create', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ ...instituteData})
        });
    },

    async updateInstitute(id, instituteData) {
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/institutes/${id}/update`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ ...instituteData})
        });
    },

    async deleteInstitute(id) {
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/institutes/${id}/delete`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        });
    },

    // Activities API
    async getActivities() {
        return this.apiCall('/api/activities');
    },

    // Admin Activities API (with ID field)
    async getAdminActivities() {
        return this.apiCall('/api/admin/activities');
    },

    async getActivity(id) {
        return this.apiCall(`/api/activities/${id}`);
    },

    async createActivity(activityData) {
        const token = localStorage.getItem('authToken');
        return this.apiCall('/api/activities/create', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ ...activityData})
        });
    },

    async updateActivity(id, activityData) {
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/activities/${id}/update`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ ...activityData})
        });
    },

    async deleteActivity(id) {
        const token = localStorage.getItem('authToken');
        return this.apiCall(`/api/activities/${id}/delete`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
            }
        });
    }
};
