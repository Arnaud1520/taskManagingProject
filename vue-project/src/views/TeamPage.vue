<template>
    <div class="team-page">
      <h1>Gestion des Équipes</h1>
      
      <!-- Liste des cartes d'équipes -->
      <div class="teams-container">
        <div v-for="team in teams" :key="team.id" class="team-card">
          <h2>{{ team.name }}</h2>
          <p>{{ team.description }}</p>
  
          <div class="members">
            <h3>Membres :</h3>
            <div v-for="member in team.members" :key="member.id" class="member-card">
              <p><strong>Nom:</strong> {{ member.name }}</p>
              <p><strong>Email:</strong> {{ member.email }}</p>
              <p><strong>Téléphone:</strong> {{ member.phone }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'TeamPage',
    data() {
      return {
        // Exemple de données fictives pour les équipes et les membres
        teams: [
          {
            id: 1,
            name: 'Équipe Alpha',
            description: 'Équipe de développement frontend.',
            members: [
              { id: 1, name: 'John Doe', email: 'john.doe@example.com', phone: '123456789' },
              { id: 2, name: 'Jane Smith', email: 'jane.smith@example.com', phone: '987654321' },
            ]
          },
          {
            id: 2,
            name: 'Équipe Beta',
            description: 'Équipe de développement backend.',
            members: [
              { id: 3, name: 'Alice Cooper', email: 'alice.cooper@example.com', phone: '234567890' },
              { id: 4, name: 'Bob Martin', email: 'bob.martin@example.com', phone: '345678901' },
            ]
          },
        ]
      };
    }
  };
  </script>
  
  <style scoped>
  .team-page {
    text-align: center;
    margin-top: 30px;
  }
  
  .teams-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
  }
  
  .team-card {
    border: 1px solid #ddd;
    border-radius: 10px;
    margin: 20px;
    padding: 20px;
    width: 250px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background-color: #f9f9f9;
  }
  
  .team-card h2 {
    margin-bottom: 10px;
  }
  
  .team-card p {
    margin: 10px 0;
  }
  
  .members {
    margin-top: 20px;
  }
  
  .member-card {
    border-top: 1px solid #ddd;
    padding-top: 10px;
  }
  
  .member-card p {
    margin: 5px 0;
  }
  
  .member-card strong {
    color: #007bff;
  }
  </style>
  