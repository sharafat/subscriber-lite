require('./bootstrap');

import Swal from 'sweetalert2';
import Alpine from 'alpinejs';
import * as Ladda from 'ladda';
import {createApp} from 'vue';
import SubscriberList from "./components/Subscriber/SubscriberList";
import FieldList from "./components/Field/FieldList";
import FieldAddEdit from "./components/Field/FieldAddEdit";

const components = {
    SubscriberList,
    FieldList,
    FieldAddEdit,
};

// Create vue app
window.app = createApp({components});

// Make window object available to all vue components as global property
app.config.globalProperties.window = window;

// Mount vue application on #app element if available
document.addEventListener(
    "DOMContentLoaded",
    () => {
        if (document.getElementById('app')) {
            window.app.mount('#app');
        }
    }
);

// Start Alpine JS
window.Alpine = Alpine;
Alpine.start();

// Attach library components to window
window.Swal = Swal;
window.Ladda = Ladda;
