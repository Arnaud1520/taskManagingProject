<template>
    <div>
      <h1>Connexion</h1>
      <form @submit.prevent="login">
        <div>
          <label>Email</label>
          <input type="email" v-model="email" required />
        </div>
        <div>
          <label>Mot de passe</label>
          <input type="password" v-model="password" required />
        </div>
        <button type="submit">Se connecter</button>
      </form>
      <p v-if="error" class="error">{{ error }}</p>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        email: '',
        password: '',
        error: null,
      };
    },
    methods: {
      async login() {
        try {
          const response = await fetch('http://localhost:8000/api/login', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              email: this.email,
              password: this.password,
            }),
          });
  
          if (!response.ok) {
            const errorData = await response.json();
            this.error = errorData.error || 'Connexion échouée';
          } else {
            const data = await response.json();
            console.log('Connexion réussie', data);
            // Stockez le token JWT ou la session utilisateur ici
          }
        } catch (e) {
          this.error = 'Erreur lors de la connexion';
        }
      },
    },
  };
  </script>
  
  <style>
  .error {
    color: red;
  }
  </style>
  