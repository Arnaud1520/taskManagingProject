<template>
  <div>
    <h1>Dashboard</h1>
    <p>Bienvenue, {{ user.name }} !</p>
    <p>Votre email : {{ user.email }}</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: null,
    };
  },
  created() {
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      this.user = JSON.parse(storedUser);
    } else {
      this.$router.push('/login'); // Rediriger si non connecté
    }
  },
};
</script>
