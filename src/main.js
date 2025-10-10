import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

// Import PrimeVue
import Aura from '@primeuix/themes/aura';
import PrimeVue from 'primevue/config';
import ConfirmationService from 'primevue/confirmationservice';
import ToastService from 'primevue/toastservice';

// Import PrimeVue components
import Avatar from 'primevue/avatar';
import Button from 'primevue/button';
import Card from 'primevue/card';
import Column from 'primevue/column';
import ConfirmDialog from 'primevue/confirmdialog';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Message from 'primevue/message';
import Password from 'primevue/password';
import TabPanel from 'primevue/tabpanel';
import TabView from 'primevue/tabview';
import Textarea from 'primevue/textarea';

// Import CSS
import '@/assets/styles.scss';

const app = createApp(App);

app.use(router);
app.use(PrimeVue, {
    theme: {
        preset: Aura,
        options: {
            darkModeSelector: '.app-dark'
        }
    }
});
app.use(ToastService);
app.use(ConfirmationService);

// On app load, proactively clear expired tokens
(() => {
    try {
        const token = localStorage.getItem('authToken');
        if (token) {
            const parts = token.split('.');
            if (parts.length === 3) {
                const payloadJson = atob(parts[1].replace(/-/g, '+').replace(/_/g, '/'));
                const payload = JSON.parse(decodeURIComponent(escape(payloadJson)));
                const now = Math.floor(Date.now() / 1000);
                if (typeof payload.exp === 'number' && payload.exp <= now) {
                    localStorage.removeItem('authToken');
                    localStorage.removeItem('adminLoggedIn');
                    localStorage.removeItem('authUser');
                }
            }
        }
    } catch (_) {
        localStorage.removeItem('authToken');
        localStorage.removeItem('adminLoggedIn');
        localStorage.removeItem('authUser');
    }
})();

// Register PrimeVue components globally
app.component('Button', Button);
app.component('InputText', InputText);
app.component('Password', Password);
app.component('Message', Message);
app.component('Card', Card);
app.component('Avatar', Avatar);
app.component('TabView', TabView);
app.component('TabPanel', TabPanel);
app.component('DataTable', DataTable);
app.component('Column', Column);
app.component('Dialog', Dialog);
app.component('Textarea', Textarea);
app.component('ConfirmDialog', ConfirmDialog);

app.mount('#app');
