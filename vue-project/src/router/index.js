import { createRouter, createWebHistory } from 'vue-router';
import Register from '../views/Register.vue'; // Assurez-vous que ce fichier existe

const routes = [
  {
    path: '/register',
    name: 'Register',
    component: Register,  // La page Register
  },
  // Ajoutez d'autres routes ici si nécessaire
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
