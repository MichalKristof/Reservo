import './bootstrap';

import {createApp, h} from 'vue'
import {createInertiaApp, Head, Link} from '@inertiajs/vue3'
import {ZiggyVue} from '../../vendor/tightenco/ziggy';
import Layout from "./Layouts/Layout.vue";
import VueDatePicker from '@vuepic/vue-datepicker';

// Import the CSS for components
import '@vuepic/vue-datepicker/dist/main.css'


createInertiaApp({
    title: (title) => `Codefolio ${title}`,
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', {eager: true})
        let page = pages[`./Pages/${name}.vue`]
        page.default.layout = page.default.layout || Layout
        return page
    },
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ZiggyVue)
            .component('VueDatePicker', VueDatePicker)
            .component('Head', Head)
            .component('Link', Link)
            .mount(el)
    },

    progress: {
        color: 'white',
        includeCSS: true,
        showSpinner: true,
    },
}).catch(error => {
    console.error('Error initializing Inertia App:', error);
});
