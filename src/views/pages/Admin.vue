<template>
  <div class="admin-container">
    <!-- Header -->
    <div class="admin-header">
      <div class="header-content">
        <div class="header-left">
          <h1 class="admin-title">
            <i class="pi pi-cog"></i>
            Admin Dashboard
          </h1>
          <p class="admin-subtitle">Manage your website content</p>
        </div>
        <div class="header-right">
          <div class="user-info">
            <Avatar 
              :label="userInitials" 
              shape="circle" 
              size="large"
              class="user-avatar"
            />
            <div class="user-details">
              <span class="username">{{ username }}</span>
              <Button 
                label="Logout" 
                icon="pi pi-sign-out" 
                severity="secondary" 
                size="small"
                @click="handleLogout"
                class="logout-btn"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon products">
          <i class="pi pi-box"></i>
        </div>
        <div class="stat-content">
          <h3>Products</h3>
          <p>Manage product brands</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon services">
          <i class="pi pi-cog"></i>
        </div>
        <div class="stat-content">
          <h3>Services</h3>
          <p>Service offerings</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon about">
          <i class="pi pi-info-circle"></i>
        </div>
        <div class="stat-content">
          <h3>About</h3>
          <p>Company information</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon downloads">
          <i class="pi pi-download"></i>
        </div>
        <div class="stat-content">
          <h3>Downloads</h3>
          <p>Brand resources</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon clients">
          <i class="pi pi-users"></i>
        </div>
        <div class="stat-content">
          <h3>Clients</h3>
          <p>University partners</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon institutes">
          <i class="pi pi-building"></i>
        </div>
        <div class="stat-content">
          <h3>Institutes</h3>
          <p>Research institutions</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon contacts">
          <i class="pi pi-map-marker"></i>
        </div>
        <div class="stat-content">
          <h3>Contacts</h3>
          <p>Office locations</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon activities">
          <i class="pi pi-star"></i>
        </div>
        <div class="stat-content">
          <h3>Activities</h3>
          <p>Company activities</p>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="admin-content">
      <Card class="main-card">
        <template #title>
          <div class="card-title">
            <i class="pi pi-edit"></i>
            Content Management
          </div>
        </template>
        <template #content>
          <TabView class="admin-tabs">
            <TabPanel>
              <template #header>
                <div class="tab-header">
                  <i class="pi pi-box"></i>
                  <span>Products</span>
                </div>
              </template>
              <ProductEditor />
            </TabPanel>
            
            <TabPanel>
              <template #header>
                <div class="tab-header">
                  <i class="pi pi-cog"></i>
                  <span>Services</span>
                </div>
              </template>
              <ServiceEditor />
            </TabPanel>
            
            <TabPanel>
              <template #header>
                <div class="tab-header">
                  <i class="pi pi-info-circle"></i>
                  <span>About</span>
                </div>
              </template>
              <AboutEditor />
            </TabPanel>
            
            <TabPanel>
              <template #header>
                <div class="tab-header">
                  <i class="pi pi-download"></i>
                  <span>Downloads</span>
                </div>
              </template>
              <DownloadEditor />
            </TabPanel>
            
            <TabPanel>
              <template #header>
                <div class="tab-header">
                  <i class="pi pi-users"></i>
                  <span>Clients</span>
                </div>
              </template>
              <ClientEditor />
            </TabPanel>
            
            <TabPanel>
              <template #header>
                <div class="tab-header">
                  <i class="pi pi-building"></i>
                  <span>Institutes</span>
                </div>
              </template>
              <InstituteEditor />
            </TabPanel>
            
            <TabPanel>
              <template #header>
                <div class="tab-header">
                  <i class="pi pi-map-marker"></i>
                  <span>Contacts</span>
                </div>
              </template>
              <ContactEditor />
            </TabPanel>
            
            <TabPanel>
              <template #header>
                <div class="tab-header">
                  <i class="pi pi-star"></i>
                  <span>Activities</span>
                </div>
              </template>
              <ActivityEditor />
            </TabPanel>
            

          </TabView>
        </template>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { ApiService } from '@/service/ApiService.js';
import AboutEditor from '@/views/pages/components/AboutEditor.vue';
import ActivityEditor from '@/views/pages/components/ActivityEditor.vue';
import ClientEditor from '@/views/pages/components/ClientEditor.vue';
import ContactEditor from '@/views/pages/components/ContactEditor.vue';
import DownloadEditor from '@/views/pages/components/DownloadEditor.vue';
import InstituteEditor from '@/views/pages/components/InstituteEditor.vue';
import ProductEditor from '@/views/pages/components/ProductEditor.vue';
import ServiceEditor from '@/views/pages/components/ServiceEditor.vue';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const username = ref('');

const userInitials = computed(() => {
  return username.value ? username.value.charAt(0).toUpperCase() : 'A';
});

const handleLogout = async () => {
  try {
    await ApiService.logout();
  } catch (_) {
    // ignore network errors on logout
  } finally {
    localStorage.removeItem('adminLoggedIn');
    localStorage.removeItem('adminUser');
    localStorage.removeItem('authToken');
    localStorage.removeItem('authUser');
    router.push('/auth/login');
  }
};



// Token validation function
const validateToken = async () => {
  const token = localStorage.getItem('authToken');
  if (!token) return false;

  try {
    const response = await fetch('https://dev.aztecsb.com/backend/web/api/auth/profile', {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      }
    });

    if (!response.ok) {
      // Token invalid or expired: clear auth state
      localStorage.removeItem('authToken');
      localStorage.removeItem('adminLoggedIn');
      localStorage.removeItem('adminUser');
      localStorage.removeItem('authUser');
      router.push('/auth/login');
      return false;
    }

    return true;
  } catch (error) {
    console.error('Token validation failed:', error);
    return false;
  }
};

onMounted(async () => {
  const isLoggedIn = localStorage.getItem('adminLoggedIn');
  const adminUser = localStorage.getItem('adminUser');
  const token = localStorage.getItem('authToken');
  const user = localStorage.getItem('authUser');
  
  if (!token) {
    router.push('/auth/login');
    return;
  }

  // Validate token with backend to ensure it's still valid
  const isValid = await validateToken();
  if (!isValid) {
    return;
  }

  // Token is valid, set username
  username.value = adminUser;

  // Set up periodic token validation (every 5 minutes)
  const tokenValidationInterval = setInterval(async () => {
    const isValid = await validateToken();
    if (!isValid) {
      clearInterval(tokenValidationInterval);
    }
  }, 5 * 60 * 1000); // 5 minutes

  // Clean up interval when component unmounts
  onUnmounted(() => {
    clearInterval(tokenValidationInterval);
  });
});
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
</style>

<style scoped>
.admin-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  padding: 0;
}

.admin-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 2rem 0;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.header-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-left h1 {
  margin: 0;
  font-family: 'Inter', 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
  font-size: 2.5rem;
  font-weight: 800;
  display: flex;
  align-items: center;
  gap: 1rem;
  letter-spacing: -0.02em;
  text-transform: uppercase;
}

.header-left h1 i {
  font-size: 2rem;
}

.admin-subtitle {
  margin: 0.5rem 0 0 0;
  font-family: 'Inter', 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
  font-size: 1.1rem;
  font-weight: 500;
  opacity: 0.9;
  letter-spacing: 0.01em;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.user-details {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 0.5rem;
}

.username {
  font-weight: 600;
  font-size: 1.1rem;
}

.logout-btn {
  font-size: 0.9rem;
}

.stats-grid {
  max-width: 1200px;
  margin: -2rem auto 2rem auto;
  padding: 0 2rem;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
  position: relative;
  z-index: 10;
}

.stat-card {
  background: white;
  border-radius: 15px;
  padding: 1.5rem;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  gap: 1rem;
  transition: all 0.3s ease;
  cursor: pointer;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.stat-icon {
  width: 60px;
  height: 60px;
  border-radius: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: white;
}

.stat-icon.products { background: linear-gradient(135deg, #ff6b6b, #ee5a24); }
.stat-icon.services { background: linear-gradient(135deg, #4ecdc4, #44a08d); }
.stat-icon.about { background: linear-gradient(135deg, #45b7d1, #96c93d); }
.stat-icon.downloads { background: linear-gradient(135deg, #f093fb, #f5576c); }
.stat-icon.clients { background: linear-gradient(135deg, #4facfe, #00f2fe); }
.stat-icon.institutes { background: linear-gradient(135deg, #667eea, #764ba2); }
.stat-icon.contacts { background: linear-gradient(135deg, #43e97b, #38f9d7); }
.stat-icon.activities { background: linear-gradient(135deg, #ffd89b, #19547b); }

.stat-content h3 {
  margin: 0 0 0.5rem 0;
  font-family: 'Inter', 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
  font-size: 1.2rem;
  font-weight: 700;
  color: #2c3e50;
  letter-spacing: -0.01em;
  text-transform: uppercase;
}

.stat-content p {
  margin: 0;
  color: #7f8c8d;
  font-size: 0.9rem;
}

.admin-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem 2rem 2rem;
}

.main-card {
  border-radius: 20px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
  border: none;
  overflow: hidden;
}

.card-title {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 1.3rem;
  font-weight: 600;
  color: #2c3e50;
}

.card-title i {
  font-size: 1.2rem;
  color: #667eea;
}

.admin-tabs {
  margin-top: 1rem;
}

.tab-header {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-family: 'Inter', 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
  font-weight: 600;
  letter-spacing: 0.01em;
  text-transform: uppercase;
}

.tab-header i {
  font-size: 1rem;
}

:deep(.p-tabview-nav) {
  background: #f8f9fa;
  border-radius: 10px 10px 0 0;
  padding: 0.5rem;
}

:deep(.p-tabview-nav li) {
  margin: 0 0.25rem;
}

:deep(.p-tabview-nav li .p-tabview-nav-link) {
  border-radius: 8px;
  padding: 0.75rem 1.5rem;
  font-weight: 500;
  transition: all 0.3s ease;
  border: none;
  background: transparent;
}

:deep(.p-tabview-nav li .p-tabview-nav-link:hover) {
  background: rgba(102, 126, 234, 0.1);
  color: #667eea;
}

:deep(.p-tabview-nav li.p-tabview-ink-bar) {
  background: linear-gradient(135deg, #667eea, #764ba2);
}

:deep(.p-tabview-panels) {
  background: white;
  border-radius: 0 0 15px 15px;
  padding: 2rem;
}

:deep(.p-card .p-card-title) {
  font-size: 1.3rem;
  font-weight: 600;
  color: #2c3e50;
  margin-bottom: 1rem;
}

:deep(.p-card .p-card-content) {
  padding: 0;
}

@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
    margin-top: -1rem;
  }
  
  .admin-content {
    padding: 0 1rem 2rem 1rem;
  }
  
  .header-left h1 {
    font-size: 2rem;
  }
}
</style>