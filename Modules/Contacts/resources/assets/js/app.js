import { createApp } from 'vue';
import ContactManager from './components/ContactManager.vue';

if (document.getElementById('contact-manager-app')) {
    createApp(ContactManager).mount('#contact-manager-app');
}
