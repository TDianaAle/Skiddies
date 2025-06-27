import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; // Importa il router

import './assets/main.css';
const app = createApp(App);

app.use(router); // Usa il router

app.mount('#app');
