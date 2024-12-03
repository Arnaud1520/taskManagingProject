import Login from '@/views/Login.vue'; // Assurez-vous que le chemin est correct
import Register from '@/views/Register.vue'; // Importez votre page Register.vue
import { createRouter, createWebHistory } from 'vue-router';

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: Login,
  },
  {
    path: '/register',
    name: 'Register',
    component: Register, // Ajoutez la route pour la page d'enregistrement
  },
  // Ajoutez d'autres routes ici si nécessaire
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;

