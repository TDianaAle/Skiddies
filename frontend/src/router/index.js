import DashboardView from '@/views/DashboardView.vue';
import HomeView from '@/views/HomeView.vue';
import LoginView from '@/views/LoginView.vue';
import RegisterView from '@/views/RegisterView.vue';
import UserPersonalizedView from '@/views/UserPersonalizedView.vue';
import PlaylistView from '@/views/PlaylistView.vue';

//pagine per studente o tutor
import StudentDashboardView from '@/views/StudentDashboardView.vue';
import TutorDashboardView from '@/views/TutorDashboardView.vue';

import { createRouter, createWebHistory } from 'vue-router';

const routes = [
  { path: '/', name: 'home', component: HomeView },
  { path: '/register', name: 'register', component: RegisterView },
  { path: '/login', name: 'login', component: LoginView },
  { path: '/dashboard', name: 'dashboard', component: DashboardView },
  { path: '/personalization', name: 'personalization', component: UserPersonalizedView },
  { path: '/playlist', name: 'playlist', component: PlaylistView },
  { path: '/tutor', name: 'tutor', component: TutorDashboardView },
  { path: '/student', name: 'student', component: StudentDashboardView }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
