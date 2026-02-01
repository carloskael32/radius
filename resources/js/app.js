import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

//datatable primevue
//import Aura from '@primevue/themes/aura'
import Aura from '@primevue/themes/aura'; // Tema por defecto (nuevo)
import Lara from '@primevue/themes/lara'; // Material-like
import Material from '@primevue/themes/material'; // Google Material


import PrimeVue from 'primevue/config';


import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar';
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import Button from 'primevue/button';


import 'primeicons/primeicons.css'; // Icons


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';



createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {


        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
               theme: {
                    preset: Lara,
                    options: {
                        prefix: 'p', // Prefijo para las clases
                        darkModeSelector: 'system', // O cambia a 'system'/'media'/'class' para modo oscuro
                        cssLayer: {
                            name: 'primevue',
                            order: 'tailwind-base, primevue, tailwind-utilities'
                        }
                    }
                }
            })
            .component('Datatable', DataTable)
            .component('Column', Column)
            .component('Toolbar', Toolbar)
            .component('InputText', InputText)
            .component('IconField', IconField)
            .component('InputIcon', InputIcon)
            .component('Button', Button)
            
            .mount(el);

    },
    progress: {
        color: '#4B5563',
    },

});
