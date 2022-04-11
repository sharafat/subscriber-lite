require('./bootstrap');

import Swal from 'sweetalert2';
import Alpine from 'alpinejs';
import {createApp} from 'vue';
import SubscriberList from "./components/Subscriber/SubscriberList";

// Create vue app
window.app = createApp({
    components: {
        SubscriberList,
    }
});

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
