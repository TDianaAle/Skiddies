import HomeView from '@/views/HomeView.vue'
import LoginView from '@/views/LoginView.vue'
import RegisterView from '@/views/RegisterView.vue'
import DashboardView from '@/views/DashboardView.vue'
import UserView from '@/views/UserView.vue'
import UserPersonalizedView from '@/views/UserPersonalizedView.vue'
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

    {
        path: '/dashboard',
        name: 'dashboard',
        component: DashboardView
    },

    {
        path: '/user',
        name: 'user',
        component: UserView
    },
    {
        path:'/personalization',
        name:'personalization',
        component: UserPersonalizedView,
    },
       
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

  export default {
  state: {
    userImageUrl: localStorage.getItem('userImageUrl') || 'https://default-avatar.com/default.png',
  },
  mutations: {
    setUserImageUrl(state, url) {
      state.userImageUrl = url;
      localStorage.setItem('userImageUrl', url); // Salva nel localStorage
    },
  },
  getters: {
    userImageUrl: (state) => state.userImageUrl,
  }
}
