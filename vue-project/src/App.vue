<template>
  <main>
    <div class="wrapper">
      <!-- Liens vers les pages login et register uniquement si non authentifié -->
      <router-link v-if="!isAuthenticated" to="/login" class="link login">Login</router-link>
      <router-link v-if="!isAuthenticated" to="/register" class="link register">Register</router-link>


      <!-- Afficher le bouton de déconnexion si l'utilisateur est authentifié -->
      <button v-if="isAuthenticated" @click="logout" class="logout-button">Déconnexion</button>
    </div>

    <!-- Le contenu de la page actuelle sera affiché ici -->
    <router-view></router-view>
  </main>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

// Déclare une variable d'état pour savoir si l'utilisateur est authentifié
const isAuthenticated = ref(false);
const router = useRouter();

// Fonction pour vérifier l'état de l'authentification
const checkAuthentication = () => {
  const token = localStorage.getItem('authToken'); // Exemple avec token stocké dans localStorage
  isAuthenticated.value = !!token; // Si le token existe, l'utilisateur est authentifié
};

// Fonction de déconnexion
const logout = () => {
  localStorage.removeItem('authToken'); // Supprimer le token d'authentification
  isAuthenticated.value = false; // Mettre à jour l'état d'authentification
  router.push('/login'); // Rediriger vers la page de connexion
};

// Vérifier l'état de l'authentification lors du montage du composant
checkAuthentication();
</script>

<style src="./assets/styles.css"></style>
<!-- Importation du fichier CSS externe -->
