import Login from '@/views/Login.vue';
import { createRouter, createWebHistory } from 'vue-router';

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: Login,
  },
  // Ajoutez d'autres routes ici si nécessaire
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
