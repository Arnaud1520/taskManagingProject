<template>
  <div>
    <h1>Register</h1>
    <form @submit.prevent="register">
      <input v-model="email" type="email" placeholder="Email" required />
      <input v-model="password" type="password" placeholder="Password" required />
      <button type="submit">Register</button>
    </form>
  </div>
</template>

<script>
export default {
  name: 'Signup',
  data() {
    return {
      email: '',
      password: '',
    }
  },
  methods: {
    register() {
      // Ajoutez ici votre logique d'enregistrement
      console.log('Registering with', this.email, this.password)
    },
  },
}
</script>


