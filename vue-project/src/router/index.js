import { createRouter, createWebHistory } from 'vue-router';
import UserProfile from '../components/UserProfile.vue';
import LoginPage from '../views/Login.vue';
import Register from '../views/Register.vue'; // Assurez-vous que ce fichier existe


const routes = [
  {
    path: '/register',
    name: 'Register',
    component: Register,  // La page Register

    
  },
  { path: '/profile/:name', name: 'UserProfile', component: UserProfile, props: true },
  { path: '/login', name: 'LoginPage', component: LoginPage },

  // autres routes...
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
