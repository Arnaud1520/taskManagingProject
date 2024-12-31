<template>
  <main>
    <!-- Effet de vagues en arrière-plan -->
    <div class="wave-container">
      <div class="wave"></div>
      <div class="wave"></div>
      <div class="wave"></div>
    </div>

    <!-- Contenu principal centré -->
    <div class="wrapper">
      <!-- Liens de navigation pour Login et Register -->
      <div v-if="!isAuthenticated" class="auth-links">
        <router-link to="/login" class="link login">Login</router-link>
        <router-link to="/register" class="link register">Register</router-link>
      </div>

      <!-- Bouton de déconnexion si authentifié -->
      <button v-if="isAuthenticated" @click="logout" class="logout-button">Déconnexion</button>
    </div>

    <!-- Contenu de la vue actuelle -->
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
