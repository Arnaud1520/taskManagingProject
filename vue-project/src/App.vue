<template>
  <header>
    <!-- Logo visible uniquement sur les pages de login ou register -->
    <img v-if="!isAuthenticated" alt="Vue logo" class="logo" src="./assets/logo.svg" width="125" height="125" />
  </header>

  <main>
    <div class="wrapper">
      <!-- Liens vers les pages login et register uniquement si non authentifié -->
      <router-link v-if="!isAuthenticated" to="/login" class="link login">Login</router-link>
      <router-link v-if="!isAuthenticated" to="/register" class="link register">Register</router-link>
      <!-- Afficher "Dashboard" si l'utilisateur est connecté -->
      <router-link v-if="isAuthenticated" to="/dashboard" class="link dashboard">Dashboard</router-link>
    </div>

    <!-- Le contenu de la page actuelle sera affiché ici -->
    <router-view></router-view>
  </main>
</template>

<script setup>
import { ref } from 'vue';

// Déclare une variable d'état pour savoir si l'utilisateur est authentifié
const isAuthenticated = ref(false);

// Fonction pour vérifier l'état de l'authentification (tu peux l'adapter selon ton backend)
const checkAuthentication = () => {
  // Vérifie la présence d'un token d'authentification ou d'une autre méthode pour l'authentification
  const token = localStorage.getItem('authToken'); // Exemple avec token stocké dans localStorage
  isAuthenticated.value = !!token; // Si le token existe, l'utilisateur est authentifié
};

// Exécute cette fonction dès le chargement du composant
checkAuthentication();
</script>

<style src="./assets/styles.css"></style>
<!-- Importation du fichier CSS externe -->
