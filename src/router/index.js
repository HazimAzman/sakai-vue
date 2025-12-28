import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'landing',
            component: () => import('@/views/pages/Landing.vue')
        },
        {
            path: '/auth/login',
            name: 'login',
            component: () => import('@/views/pages/auth/Login.vue')
        },
        {
            path: '/admin',
            name: 'admin',
            component: () => import('@/views/pages/Admin.vue'),
            meta: { requiresAuth: true }
        },
        // Brand redirects (legacy names)
        {
            path: '/products/baxvision',
            redirect: '/products/raxvision'
        },
        {
            path: '/products/baxvision/:line',
            redirect: to => `/products/raxvision/${to.params.line}`
        },
        // Brand page (must come before generic id route)
        {
            path: '/products/:brand',
            name: 'brand-page',
            component: () => import('@/views/pages/BrandPage.vue')
        },
        // Optional legacy routes kept but not used for brand pages
        {
            path: '/products',
            name: 'products',
            component: () => import('@/views/pages/Products.vue')
        },
        {
            path: '/products/:id',
            name: 'product-detail',
            component: () => import('@/views/pages/Products.vue')
        },
        {
            path: '/products/hettich/eba200-200s',
            name: 'product-hettich-eba200-200s',
            component: () => import('@/views/pages/products/HettichEba200.vue')
        },
        {
            path: '/products/hettich/universal320-320r',
            name: 'product-hettich-universal320-320r',
            component: () => import('@/views/pages/products/HettichUniversal320.vue')
        },
        {
            path: '/products/hettich/rotofix32a',
            name: 'product-hettich-rotofix32a',
            component: () => import('@/views/pages/products/HettichRotofix32a.vue')
        },
        {
            path: '/products/hettich/mikro200-200r',
            name: 'product-hettich-mikro200-200r',
            component: () => import('@/views/pages/products/HettichMikro200.vue')
        },
        {
            path: '/products/hettich/rotina380-380r',
            name: 'product-hettich-rotina380-380r',
            component: () => import('@/views/pages/products/HettichRotina380.vue')
        },
        {
            path: '/products/scilab/wisebath',
            name: 'product-scilab-wisebath',
            component: () => import('@/views/pages/products/ScilabWisebath.vue')
        },
        {
            path: '/products/thermofisher/genesys150',
            name: 'product-thermofisher-genesys150',
            component: () => import('@/views/pages/products/ThermoFisherGenesys150.vue')
        },
        {
            path: '/products/raxvision/bi400',
            name: 'product-raxvision-bi400',
            component: () => import('@/views/pages/products/BaxVisionBi400.vue')
        },
  
        {
            path: '/privacy-policy',
            name: 'privacy-policy',
            component: () => import('@/views/pages/PrivacyPolicy.vue')
        },
        {
            path: '/auth/access',
            name: 'access',
            component: () => import('@/views/pages/auth/Access.vue')
        },
        {
            path: '/auth/error',
            name: 'error',
            component: () => import('@/views/pages/auth/Error.vue')
        },
        {
            path: '/:pathMatch(.*)*',
            name: 'notfound',
            component: () => import('@/views/pages/NotFound.vue')
        }
    ]
});

// Authentication guard with token validation/expiry check
router.beforeEach(async (to, _from, next) => {
    const token = localStorage.getItem('authToken');
    const stillLoggedIn = !!localStorage.getItem('adminLoggedIn');

    // If no token, redirect to login for protected routes
    if (to.meta.requiresAuth && !token) {
        next('/auth/login');
        return;
    }

    // If on login page and already logged in with valid token, redirect to admin
    if (to.path === '/auth/login' && stillLoggedIn && token) {
        next('/admin');
        return;
    }

    // If token exists, validate it with the backend
    if (token && to.meta.requiresAuth) {
        try {
            // Check local JWT expiry first (fast check)
            const parts = token.split('.');
            if (parts.length === 3) {
                try {
                    const payloadJson = atob(parts[1].replace(/-/g, '+').replace(/_/g, '/'));
                    const payload = JSON.parse(decodeURIComponent(escape(payloadJson)));
                    const now = Math.floor(Date.now() / 1000);
                    if (typeof payload.exp === 'number' && payload.exp <= now) {
                        // Token expired locally: clear auth state
                        localStorage.removeItem('authToken');
                        localStorage.removeItem('adminLoggedIn');
                        localStorage.removeItem('adminUser');
                        localStorage.removeItem('authUser');
                        next('/auth/login');
                        return;
                    }
                } catch (_) {
                    // On parse error, treat as invalid token
                    localStorage.removeItem('authToken');
                    localStorage.removeItem('adminLoggedIn');
                    localStorage.removeItem('adminUser');
                    localStorage.removeItem('authUser');
                    next('/auth/login');
                    return;
                }
            }

            // Validate token with backend (check if still exists in database)
            const response = await fetch('/api/auth/profile', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                // Token invalid or expired on server: clear auth state
                localStorage.removeItem('authToken');
                localStorage.removeItem('adminLoggedIn');
                localStorage.removeItem('adminUser');
                localStorage.removeItem('authUser');
                next('/auth/login');
                return;
            }

            // Token is valid, allow navigation
            next();
        } catch (error) {
            // Network error or other issue: clear auth state for safety
            console.error('Token validation failed:', error);
            localStorage.removeItem('authToken');
            localStorage.removeItem('adminLoggedIn');
            localStorage.removeItem('adminUser');
            localStorage.removeItem('authUser');
            next('/auth/login');
        }
    } else {
        // No token required for this route
        next();
    }
});

export default router;