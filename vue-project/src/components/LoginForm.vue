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
import axios from 'axios'

export default {
  data() {
    return {
      email: '',
      password: '',
      error: null,
    }
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('http://localhost:8000/api/login', {
          email: this.email,
          password: this.password,
        })

        if (response.data.message === 'Connexion réussie.') {
          // Gérer la connexion réussie (par exemple, stocker le token ou rediriger l'utilisateur)
          this.$router.push('/dashboard')
        }
      } catch (e) {
        this.error = e.response?.data?.message || 'Erreur lors de la connexion'
      }
    },
  },
}
</script>

<style>
.error {
  color: red;
}
</style>
