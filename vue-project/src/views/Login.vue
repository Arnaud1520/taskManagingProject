<template>
  <div class="login-container">
    <h1>Connexion</h1>
    <form @submit.prevent="handleLogin">
      <div class="form-group">
        <label for="email">Email</label>
        <input
          type="email"
          id="email"
          v-model="form.email"
          required
          placeholder="Entrez votre email"
        />
      </div>

      <div class="form-group">
        <label for="password">Mot de passe</label>
        <input
          type="password"
          id="password"
          v-model="form.password"
          required
          placeholder="Entrez votre mot de passe"
        />
      </div>

      <button type="submit">Se connecter</button>
    </form>

    <div v-if="error" class="error-message">{{ error }}</div>
  </div>
</template>

---

### **Script Section**
```vue
<script>
import axios from "axios";

export default {
  data() {
    return {
      form: {
        email: "",
        password: "",
      },
      error: null,
    };
  },
  methods: {
    async handleLogin() {
      this.error = null;

      try {
        const response = await axios.post("/api/login", {
          email: this.form.email,
          password: this.form.password,
        });

        // Exemple de gestion d'une réponse de succès
        alert("Connexion réussie !");
        this.$router.push("/dashboard"); // Remplacez "/dashboard" par la route de la page principale après connexion
      } catch (err) {
        // Gestion d'erreur
        this.error = err.response?.data?.message || "Une erreur est survenue.";
      }
    },
  },
};
</script>
