<template>
  <div class="login-container">
    <h1>Connexion</h1>
    <form @submit.prevent="login" class="login-form">
      <div class="input-group">
        <label for="email">Email</label>
        <input id="email" type="email" v-model="email" required />
      </div>
      <div class="input-group">
        <label for="password">Mot de passe</label>
        <input id="password" type="password" v-model="password" required />
      </div>
      <button type="submit" class="submit-button">Se connecter</button>
    </form>
    <p v-if="error" class="error-message">{{ error }}</p>
  </div>
</template>


<script>
import axios from 'axios';


export default {
  data() {
    return {
      email: '',
      password: '',
      error: null, // Gestion des erreurs pour l'affichage
    };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('http://localhost:8000/api/login', {
          email: this.email.trim(), // Supprime les espaces accidentels
          password: this.password,
        });

        if (response.data.message === 'Connexion réussie.') {
          // Sauvegarder le token d'authentification dans le localStorage (ou sessionStorage)
          localStorage.setItem('authToken', response.data.token);

          localStorage.setItem('user', JSON.stringify(response.data.user)); // Sauvegarde les infos utilisateur
          
          // Récupérer le nom de l'utilisateur
          const userName = response.data.user.name;

          // Rediriger vers le dashboard avec le nom de l'utilisateur
          this.$router.push({ name: 'Dashboard', params: { name: userName } });
        }
      } catch (e) {
        // Gestion d'erreur avec un log clair
        console.error('Erreur lors de la connexion :', e);
        this.error = e.response?.data?.message || 'Erreur interne.';
      }
    },
  },
};
</script>

<style src="../assets/Login.css"></style>
