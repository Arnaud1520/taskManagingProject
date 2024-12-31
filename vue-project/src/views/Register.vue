<template>
  <div class="register-container">
    <h1 class="title">S'inscrire</h1>
    <form @submit.prevent="register" class="register-form">
      <div class="form-group">
        <label for="name">Nom</label>
        <input type="text" v-model="name" id="name" required placeholder="Entrez votre nom" />
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" v-model="email" id="email" required placeholder="Entrez votre email" />
      </div>
      <div class="form-group">
        <label for="password">Mot de passe</label>
        <input
          type="password"
          v-model="password"
          id="password"
          required
          placeholder="Entrez votre mot de passe"
        />
      </div>
      <div class="form-group">
        <label for="confirm-password">Confirmez le mot de passe</label>
        <input 
          type="password" 
          v-model="confirmPassword" 
          id="confirm-password" 
          required 
          placeholder="Confirmez votre mot de passe" 
        />
        <span v-if="passwordMismatch" class="error-message">Les mots de passe ne correspondent pas.</span>
      </div>
      <div class="form-group">
        <label for="phone">Téléphone</label>
        <input
          type="text"
          v-model="phone"
          id="phone"
          required
          placeholder="Entrez votre numéro de téléphone"
        />
      </div>

      <!-- Cacher le bouton une fois l'utilisateur inscrit -->
      <button v-if="!isRegistered" type="submit" class="submit-btn" :disabled="passwordMismatch">S'inscrire</button>
    </form>

    
  </div>
</template>

<script>
import axios from 'axios';
import '../assets/RegisterForm.css';

export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      confirmPassword: '',
      phone: '',
      isRegistered: false,  // Ajout d'un état pour vérifier si l'utilisateur est inscrit
    };
  },
  computed: {
    passwordMismatch() {
      return this.password && this.confirmPassword && this.password !== this.confirmPassword;
    },
  },
  methods: {
    async register() {
      if (this.passwordMismatch) {
        alert('Les mots de passe ne correspondent pas.');
        return;
      }

      try {
        const response = await axios.post('http://localhost:8000/api/register', {
          name: this.name,
          email: this.email,
          password: this.password,
          phone: this.phone,
        })

        const { name } = response.data;

        // L'inscription est réussie, masquer les boutons et rediriger
        this.isRegistered = true;
        this.$router.push({ name: 'Dashboard', params: { name } });

      } catch (error) {
        console.error('Error registering user:', error.response.data);
        alert('Erreur lors de l\'inscription.');
      }
    },
  },
}
</script>
