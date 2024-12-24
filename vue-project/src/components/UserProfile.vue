<template>
  <!-- Conteneur principal pour le profil -->
  <div class="profile-container">
    <!-- Vagues animées de fond -->
    <div class="context"></div>

    <div class="area">
      <ul class="circles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
      </ul>

      <div class="profile-header">
        <!-- Avatar de l'utilisateur -->
        <img :src="avatarUrl" alt="Avatar" class="avatar" />

        <!-- Nom de l'utilisateur -->
        <span class="username">{{ name }}</span>

        <!-- Bouton de déconnexion -->
        <button class="logout-btn" @click="logout">Déconnexion</button>
      </div>

      <!-- Message de bienvenue -->
      <p class="welcome-message">Bienvenue sur votre profil !</p>

      <!-- Bouton d'ajout de tâche -->
      <div class="task-button-container">
        <button class="add-task-btn" @click="addTask">Ajouter une nouvelle tâche</button>
      </div>

      <!-- Section des colonnes -->
      <div class="columns-container">
        <div class="column">
          <h3>Terminé</h3>
          <ul>
            <li></li>
            <li></li>
          </ul>
        </div>
        <div class="column">
          <h3>A faire</h3>
          <ul>
            <li></li>
            <li></li>
          </ul>
        </div>
        <div class="column">
          <h3>En cours</h3>
          <ul>
            <li></li>
            <li></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['name'],
  computed: {
    avatarUrl() {
      return `https://api.dicebear.com/6.x/avataaars/svg?seed=${encodeURIComponent(this.name)}`;
    },
  },
  methods: {
    logout() {
      // Logique de déconnexion
      console.log("Déconnexion...");

      // Supprimer le token d'authentification du localStorage (ou sessionStorage)
      localStorage.removeItem('userToken'); // ou sessionStorage.removeItem('userToken');

      // Si vous utilisez Vuex pour gérer l'état de l'utilisateur, réinitialisez l'état
      if (this.$store) {
        this.$store.commit('resetUser'); // Vous devrez définir un mutation pour réinitialiser l'état utilisateur
      }

      // Redirection vers la page de connexion après déconnexion
      this.$router.push({ name: 'LoginPage' });
    },
    addTask() {
      // Logique pour ajouter une nouvelle tâche
      console.log("Ajouter une nouvelle tâche !");
    },
  },
};
</script>

<style src="../assets/UserProfile.css"></style>
