import Dashboard from '@/views/Dashboard.vue';
import { createRouter, createWebHistory } from 'vue-router';
import UserProfile from '../components/UserProfile.vue';
import LoginPage from '../views/Login.vue';
import Register from '../views/Register.vue';

// Routes définies pour l'application
const routes = [
  {
    path: '/register',
    name: 'Register',
    component: Register,  // La page Register    
  },
  {
    path: '/profile/:name',
    name: 'UserProfile', 
    component: UserProfile, 
  },
  {
    path: '/dashboard/:name',
    name: 'Dashboard', 
    component: Dashboard, 
    props: true,
    meta: { requiresAuth: true }  // Cette route nécessite une authentification
  },
  {
    path: '/login', 
    name: 'LoginPage', 
    component: LoginPage 
  },
  {
    path: '/teams',
    name: 'TeamPage',
    component: () => import('@/views/TeamPage.vue'), // Remplace par le bon chemin pour ton composant TeamPage
  },
  // Page par défaut redirige vers /login si aucune autre route ne correspond
  { path: '/', redirect: '/login' },
];

// Création du routeur
const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Garde de route pour vérifier l'authentification avant chaque accès à une route protégée
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('authToken'); // Vérifie si le token est présent dans le localStorage
  const isAuthenticated = !!token; // Si le token existe, l'utilisateur est authentifié

  if (to.meta.requiresAuth && !isAuthenticated) {
    // Si la route nécessite une authentification et que l'utilisateur n'est pas connecté, redirige vers /login
    next('/login');
  } else if (!to.meta.requiresAuth && isAuthenticated) {
    // Si la route n'est pas protégée et que l'utilisateur est déjà connecté, redirige vers /dashboard
    next('/dashboard');
  } else {
    // Sinon, laisse passer la navigation
    next();
  }
});

export default router;
