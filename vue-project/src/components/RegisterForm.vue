<template>
    <div class="register">
      <h2>Register</h2>
  
      <form @submit.prevent="registerUser">
        <div>
          <label for="name">Name:</label>
          <input
            type="text"
            id="name"
            v-model="name"
            required
          />
        </div>
  
        <div>
          <label for="email">Email:</label>
          <input
            type="email"
            id="email"
            v-model="email"
            required
          />
        </div>
  
        <div>
          <label for="password">Password:</label>
          <input
            type="password"
            id="password"
            v-model="password"
            required
          />
        </div>
  
        <div>
          <label for="confirmPassword">Confirm Password:</label>
          <input
            type="password"
            id="confirmPassword"
            v-model="confirmPassword"
            required
          />
        </div>
  
        <button type="submit">Register</button>
  
        <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const name = ref('');
  const email = ref('');
  const password = ref('');
  const confirmPassword = ref('');
  const errorMessage = ref('');
  
  const registerUser = async () => {
  if (password.value !== confirmPassword.value) {
    errorMessage.value = 'Passwords do not match!';
    return;
  }

  try {
    const response = await axios.post('/api/register', {
      name: name.value,
      email: email.value,
      password: password.value
    });

    console.log('User registered successfully:', response.data);

    // Réinitialiser les champs après soumission réussie
    name.value = '';
    email.value = '';
    password.value = '';
    confirmPassword.value = '';
    errorMessage.value = '';
  } catch (error) {
    errorMessage.value = 'Registration failed. Please try again.';
  }
};
  </script>
  
  <style scoped>
  .register {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
  }
  
  h2 {
    text-align: center;
    margin-bottom: 20px;
  }
  
  form {
    display: flex;
    flex-direction: column;
  }
  
  form div {
    margin-bottom: 10px;
  }
  
  label {
    font-weight: bold;
  }
  
  input {
    padding: 8px;
    font-size: 1rem;
    margin-top: 5px;
    width: 100%;
    box-sizing: border-box;
  }
  
  button {
    padding: 10px;
    font-size: 1rem;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #45a049;
  }
  
  .error {
    color: red;
    font-size: 0.9rem;
    text-align: center;
  }
  </style>
  