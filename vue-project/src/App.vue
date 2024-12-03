<template>
  <header>
    <img alt="Vue logo" class="logo" src="./assets/logo.svg" width="125" height="125" />
  </header>

  <main>
    <div class="wrapper">
      <!-- Ajouter les liens vers la page de connexion et la page d'enregistrement -->
      <router-link to="/login" class="link">Login</router-link>
      <router-link to="/register" class="link">Register</router-link>
    </div>

    <!-- Le contenu de la page actuelle sera affiché ici -->
    <router-view></router-view>
  </main>
</template>

<script setup>
// Vous pouvez importer des composants ici si nécessaire
</script>

<style scoped>
header {
  line-height: 1.5;
  text-align: center;
}

.logo {
  display: block;
  margin: 0 auto 2rem;
}

.wrapper {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 20px;
}

.link {
  text-decoration: none;
  color: #007bff;
  font-weight: bold;
}

.link:hover {
  text-decoration: underline;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }
}
</style>
