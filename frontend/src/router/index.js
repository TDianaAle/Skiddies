import HomeView from '@/views/HomeView.vue'
import LoginView from '@/views/LoginView.vue'
import RegisterView from '@/views/RegisterView.vue'
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/',
        name: 'home',
        component: HomeView
    },
    {
    path: '/register',
    name: 'register',
    component: RegisterView
    },
    {
        path: '/login',
        name: 'login',
        component: LoginView
    },
  // qui dashboard
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
