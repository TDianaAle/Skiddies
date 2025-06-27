
import RegisterView from '@/views/RegisterView.vue';
import { createRouter, createWebHistory } from 'vue-router';


const routes = [
    {
        path: '/register',
        component: RegisterView,
    },
    {
        path: '/',
        redirect: '/login', // Puoi cambiare questa rotta di redirezione in base alle tue necessità
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
