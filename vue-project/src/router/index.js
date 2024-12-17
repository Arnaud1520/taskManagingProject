import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue' // Assurez-vous que ce fichier existe
import Register from '../views/Register.vue' // Assurez-vous que ce fichier existe

const routes = [
  {
    path: '/register',
    name: 'Register',
    component: Register, // La page d'inscription
  },
  {
    path: '/login',
    name: 'Login',
    component: Login, // La page de connexion
  },
  {
    path: '/',
    redirect: '/login', // Redirige vers la page de connexion par défaut
  },
  // Ajoutez d'autres routes ici si nécessaire
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
