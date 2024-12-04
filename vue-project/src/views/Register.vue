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
        <input type="password" v-model="password" id="password" required placeholder="Entrez votre mot de passe" />
      </div>
      <div class="form-group">
        <label for="phone">Téléphone</label>
        <input type="text" v-model="phone" id="phone" required placeholder="Entrez votre numéro de téléphone" />
      </div>
      <button type="submit" class="submit-btn">S'inscrire</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
import '../assets/RegisterForm.css'; // Importation du fichier CSS

export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      phone: '',
    };
  },
  methods: {
    async register() {
      try {
        const response = await axios.post('http://localhost:8000/api/register', {
          name: this.name,
          email: this.email,
          password: this.password,
          phone: this.phone,
        });

        console.log(response.data.message);  // Afficher le message de succès
        // Vous pouvez ajouter une redirection vers la page de connexion ou un message de succès ici
      } catch (error) {
        console.error('Error registering user:', error.response.data);
        // Afficher un message d'erreur à l'utilisateur
      }
    },
  },
};
</script>
