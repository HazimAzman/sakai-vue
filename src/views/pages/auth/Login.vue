<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const credentials = reactive({
  username: '',
  password: ''
});

const errors = reactive({
  username: '',
  password: ''
});

const isLoading = ref(false);
const loginError = ref('');
const checked = ref(false);

const validateForm = () => {
  errors.username = '';
  errors.password = '';
  
  if (!credentials.username) {
    errors.username = 'Username is required';
  }
  
  if (!credentials.password) {
    errors.password = 'Password is required';
  }
  
  return !errors.username && !errors.password;
};

// Determine API base URL: prefer Vite env, fallback to relative /api
const API_BASE_URL = 'https://dev.aztecsb.com/backend/web';

const handleLogin = async () => {
  if (!validateForm()) return;

  isLoading.value = true;
  loginError.value = '';

  try {
    // Support both proxied "/api" and absolute base URLs
    const endpoint = API_BASE_URL
      ? `${API_BASE_URL}/api/auth/login`
      : `/api/auth/login`;

    const resp = await fetch(endpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        username: credentials.username,
        password: credentials.password
      })
    });

    const json = await resp.json();

    if (!resp.ok || json?.success === false) {
      throw new Error(json?.message || 'Invalid username or password');
    }

    const token = json?.data?.token;
    const user = json?.data?.user;

    if (!token) {
      throw new Error('No token returned by server');
    }

    // Persist auth
    localStorage.setItem('adminLoggedIn', 'true');
    localStorage.setItem('adminUser', user?.username || credentials.username);
    localStorage.setItem('authToken', token);
    if (user) localStorage.setItem('authUser', JSON.stringify(user));

    // Navigate
    router.push('/admin');
  } catch (err) {
    loginError.value = err?.message || 'Login failed';
  } finally {
    isLoading.value = false;
  }
};
</script>

<template>
    <div class="bg-surface-50 dark:bg-surface-950 flex items-center justify-center min-h-screen min-w-[100vw] overflow-hidden">
        <div class="flex flex-col items-center justify-center">
            <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full bg-surface-0 dark:bg-surface-900 py-20 px-8 sm:px-20" style="border-radius: 53px">
                    <div class="text-center mb-8">
                        <!-- Aztec Logo -->
                        <div class="mb-8 flex justify-center">
                            <div class="relative">
                                <!-- Red AZT text -->
                                <div class="text-6xl font-bold text-red-600 relative z-10" style="font-family: 'Arial Black', sans-serif;">
                                    AZT
                                </div>
                                <!-- Yellow oval behind -->
                                <div class="absolute -top-2 -left-2 w-20 h-12 bg-yellow-400 rounded-full opacity-80"></div>
                                <!-- Grey oval behind -->
                                <div class="absolute -top-1 -left-1 w-20 h-12 bg-gray-400 rounded-full opacity-60"></div>
                            </div>
                        </div>
                        
                        <!-- Company Name -->
                        <div class="text-surface-900 dark:text-surface-0 text-2xl font-bold mb-2">AZTEC SINAR SDN BHD</div>
                        <div class="text-surface-600 dark:text-surface-400 text-sm italic mb-4">(Comp. No : 661829-W)</div>
                        
                        <div class="text-surface-900 dark:text-surface-0 text-3xl font-medium mb-4">Admin Login</div>
                        <span class="text-muted-color font-medium">Enter your credentials to access the admin panel</span>
                    </div>

                    <form @submit.prevent="handleLogin">
                        <div>
                            <label for="username" class="block text-surface-900 dark:text-surface-0 text-xl font-medium mb-2">Username</label>
                            <InputText 
                                id="username" 
                                type="text" 
                                placeholder="Enter username" 
                                class="w-full md:w-[30rem] mb-2" 
                                v-model="credentials.username"
                                :class="{ 'p-invalid': errors.username }"
                            />
                            <small v-if="errors.username" class="p-error block mb-4">{{ errors.username }}</small>

                            <label for="password" class="block text-surface-900 dark:text-surface-0 font-medium text-xl mb-2">Password</label>
                            <Password 
                                id="password" 
                                v-model="credentials.password" 
                                placeholder="Enter password" 
                                :toggleMask="true" 
                                class="mb-2" 
                                fluid 
                                :feedback="false"
                                :class="{ 'p-invalid': errors.password }"
                            ></Password>
                            <small v-if="errors.password" class="p-error block mb-4">{{ errors.password }}</small>

                            <div class="flex items-center justify-between mt-2 mb-8 gap-8">
                                <div class="flex items-center">
                                    <Checkbox v-model="checked" id="rememberme1" binary class="mr-2"></Checkbox>
                                    <label for="rememberme1">Remember me</label>
                                </div>
                                <span class="font-medium no-underline ml-2 text-right cursor-pointer text-primary">Forgot password?</span>
                            </div>
                            
                            <Button 
                                type="submit"
                                :label="isLoading ? 'Signing In...' : 'Sign In'" 
                                class="w-full" 
                                :loading="isLoading"
                            />
                            
                            <!-- Error Message -->
                            <div v-if="loginError" class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                                {{ loginError }}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.pi-eye {
    transform: scale(1.6);
    margin-right: 1rem;
}

.pi-eye-slash {
    transform: scale(1.6);
    margin-right: 1rem;
}
</style>
