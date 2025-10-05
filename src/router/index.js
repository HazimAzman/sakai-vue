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

// Authentication guard
router.beforeEach((to, _from, next) => {
    const isLoggedIn = localStorage.getItem('adminLoggedIn');
    
    if (to.meta.requiresAuth && !isLoggedIn) {
        next('/auth/login');
    } else if (to.path === '/auth/login' && isLoggedIn) {
        next('/admin');
    } else {
        next();
    }
});

export default router;