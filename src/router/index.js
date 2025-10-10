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
router.beforeEach((to, _from, next) => {
    const isLoggedIn = localStorage.getItem('adminLoggedIn');
    const token = localStorage.getItem('authToken');

    // If a token exists, check expiry (JWT exp claim) on navigation
    if (token) {
        const parts = token.split('.');
        if (parts.length === 3) {
            try {
                const payloadJson = atob(parts[1].replace(/-/g, '+').replace(/_/g, '/'));
                const payload = JSON.parse(decodeURIComponent(escape(payloadJson)));
                const now = Math.floor(Date.now() / 1000);
                if (typeof payload.exp === 'number' && payload.exp <= now) {
                    // Token expired: clear auth state
                    localStorage.removeItem('authToken');
                    localStorage.removeItem('adminLoggedIn');
                }
            } catch (_) {
                // On parse error, treat as invalid token
                localStorage.removeItem('authToken');
                localStorage.removeItem('adminLoggedIn');
            }
        }
    }

    const stillLoggedIn = !!localStorage.getItem('adminLoggedIn');

    if (to.meta.requiresAuth && !stillLoggedIn) {
        next('/auth/login');
    } else if (to.path === '/auth/login' && stillLoggedIn) {
        next('/admin');
    } else {
        next();
    }
});

export default router;