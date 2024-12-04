<template>
  <header>
    <img alt="Vue logo" class="logo" src="./assets/logo.svg" width="125" height="125" />
  </header>

  <main>
    <div class="wrapper">
      <!-- Ajouter les liens vers la page de connexion et la page d'enregistrement -->
      <router-link to="/login" class="link">Login</router-link>
      <router-link to="/register" class="link register">Register</router-link>  <!-- Ajouter la classe "register" pour le bouton Register -->
    </div>

    <!-- Le contenu de la page actuelle sera affiché ici -->
    <router-view></router-view>
  </main>
</template>

<script setup>
// Vous pouvez importer des composants ici si nécessaire
</script>

<style src="./assets/styles.css"></style> <!-- Importation du fichier CSS externe -->
