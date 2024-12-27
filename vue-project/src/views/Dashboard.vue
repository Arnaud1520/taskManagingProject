<template>
  <div class="dashboard-page">
    <div class="sidebar">
      <!-- Avatar et Nom de l'utilisateur centrés et abaissés -->
      <div class="profile-header">
        <img :src="avatarUrl" alt="Avatar" class="avatar" />
        <span class="username">{{ name }}</span>
      </div>

      <!-- Menu de navigation -->
      <ul>
        <li @click="activeSection = 'tasks'" :class="{ active: activeSection === 'tasks' }">Tâches</li>
        <li @click="activeSection = 'teams'" :class="{ active: activeSection === 'teams' }">Équipes</li>
      </ul>
    </div>

    <div class="content">
      <h1>Tableau de bord</h1>

      <!-- Section des Tâches -->
      <section v-if="activeSection === 'tasks'" class="tasks-section">
        <h2>Gestion des Tâches</h2>

        <!-- Bouton d'ajout de tâche -->
        <div class="task-button-container">
          <button class="add-task-btn" @click="addTask">Ajouter une nouvelle tâche</button>
        </div>

        <!-- Tâches en cours -->
        <div class="tasks-status-section">
          <h3>En cours</h3>
          <div class="tasks-container">
            <div class="task-card" v-for="task in filteredTasks('inProgress')" :key="task.id">
              <i class="edit-icon fas fa-pen" @click="editTask(task.id)"></i>
              <h3>{{ task.title }}</h3>
              <p>{{ task.description }}</p>
              <p><strong>Priorité:</strong> {{ task.priority }}</p>
              <p><strong>Status:</strong> {{ task.status }}</p>
            </div>
          </div>
        </div>

        <!-- Tâches à faire -->
        <div class="tasks-status-section">
          <h3>À faire</h3>
          <div class="tasks-container">
            <div class="task-card" v-for="task in filteredTasks('toDo')" :key="task.id">
              <i class="edit-icon fas fa-pen" @click="editTask(task.id)"></i>
              <h3>{{ task.title }}</h3>
              <p>{{ task.description }}</p>
              <p><strong>Priorité:</strong> {{ task.priority }}</p>
              <p><strong>Status:</strong> {{ task.status }}</p>
            </div>
          </div>
        </div>

        <!-- Tâches terminées -->
        <div class="tasks-status-section">
          <h3>Terminées</h3>
          <div class="tasks-container">
            <div class="task-card" v-for="task in filteredTasks('done')" :key="task.id">
              <i class="edit-icon fas fa-pen" @click="editTask(task.id)"></i>
              <h3>{{ task.title }}</h3>
              <p>{{ task.description }}</p>
              <p><strong>Priorité:</strong> {{ task.priority }}</p>
              <p><strong>Status:</strong> {{ task.status }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Section des Équipes -->
      <section v-if="activeSection === 'teams'" class="teams-section">
        <h2>Gestion des Équipes</h2>
        <div class="add-team-container">
          <button class="add-team-btn" @click="showTeamForm = true">+</button>
        </div>

        <!-- Pop-up pour la création d'une équipe -->
        <div v-if="showTeamForm" class="popup-overlay" @click.self="showTeamForm = false">
          <div class="popup">
            <h3>Créer une nouvelle équipe</h3>
            <form @submit.prevent="createTeam">
              <div>
                <label for="teamName">Nom de l'équipe :</label>
                <input type="text" id="teamName" v-model="newTeam.name" required />
              </div>
              <div>
                <label for="teamDescription">Description :</label>
                <textarea id="teamDescription" v-model="newTeam.description" required></textarea>
              </div>
              <button type="submit">Créer</button>
              <button type="button" @click="showTeamForm = false">Annuler</button>
            </form>
          </div>
        </div>

        <div class="teams-container">
          <div v-for="team in teams" :key="team.id" class="team-card">
            <i class="edit-icon fas fa-pen" @click="editTeam(team.id)"></i>
            <h3>{{ team.name }}</h3>
            <p>{{ team.description }}</p>
            <div class="members">
              <h4>Membres :</h4>
              <div v-for="member in team.members" :key="member.id" class="member-card">
                <p><strong>Nom:</strong> {{ member.name }}</p>
                <p><strong>Email:</strong> {{ member.email }}</p>
                <p><strong>Téléphone:</strong> {{ member.phone }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>


<script>
export default {
  props: ['name'],
  data() {
    return {
      activeSection: 'tasks', // Section active par défaut
      showTeamForm: false, // Gestion de la pop-up pour les équipes
      newTeam: {           // Données du formulaire de création d'équipe
        name: '',
        description: ''
      },
      tasks: [
        { id: 1, title: 'Développer la fonctionnalité X', description: 'Développer la fonctionnalité X dans l\'application', status: 'inProgress', priority: 1 },
        { id: 2, title: 'Corriger le bug Y', description: 'Fixer le bug Y qui empêche l\'accès à la page Z', status: 'toDo', priority: 2 },
        { id: 3, title: 'Test de la fonctionnalité Z', description: 'Effectuer les tests de la fonctionnalité Z', status: 'done', priority: 3 },
      ],
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
  },
  computed: {
    avatarUrl() {
      return `https://api.dicebear.com/6.x/avataaars/svg?seed=${encodeURIComponent(this.name)}`;
    },
  },
  methods: {
    createTeam() {
      const newTeam = {
        id: this.teams.length + 1,
        ...this.newTeam,
        members: [] // Initialement, aucune membre dans la nouvelle équipe
      };
      this.teams.push(newTeam);

      // Réinitialiser les données et fermer la pop-up
      this.newTeam = { name: '', description: '' };
      this.showTeamForm = false;
    },
    addTask() {
      console.log("Ajouter une nouvelle tâche !");
    },
    filteredTasks(status) {
      return this.tasks.filter(task => task.status === status);
    }
  }
};
</script>

<style scoped>
.dashboard-page {
  display: flex;
}

.sidebar {
  width: 250px;
  background-color: #2c3e50;
  color: white;
  padding: 20px;
  height: 100vh;
  position: fixed;
}

.profile-header {
  display: flex;
  flex-direction: column; /* Dispose l'avatar et le nom en colonne */
  align-items: center; /* Centre horizontalement */
  justify-content: center; /* Centre verticalement */
  margin-top: 100px; /* Abaisse le tout */
  margin-left: 51px; /* Abaisse le tout */
}

.avatar {
  width: 60px; /* Taille de l'avatar */
  height: 60px;
  border-radius: 50%;
  margin-bottom: 10px; /* Espace entre l'avatar et le nom */
}

.username {
  font-size: 1.2rem; /* Taille du texte */
  font-weight: bold;
  color: white; /* Couleur du texte pour contraster avec le fond */
  text-align: center; /* Centre le texte */
}

.sidebar ul {
  list-style-type: none;
  padding: 0;
}

.sidebar ul li {
  padding: 10px;
  cursor: pointer;
  border-bottom: 1px solid #34495e;
}

.sidebar ul li:hover {
  background-color: #34495e;
}

.sidebar ul li.active {
  background-color: #16a085;
}

.content {
  margin-left: 250px;
  padding: 40px 20px 20px 20px;
  width: 100%;
}

h1 {
  text-align: center;
  margin-bottom: 40px;
}

.tasks-section,
.teams-section {
  margin-bottom: 50px;
}

.task-button-container {
  margin-bottom: 20px;
}

.add-task-btn {
  padding: 10px 20px;
  background-color: #4CAF50;
  color: white;
  border: none;
  cursor: pointer;
  font-size: 1rem;
  border-radius: 5px;
}

.tasks-status-section {
  margin-bottom: 30px;
}

.tasks-container {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.task-card {
  width: 300px;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 8px;
  background-color: #f9f9f9;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.task-card h3 {
  font-size: 1.2rem;
}

.task-card p {
  margin: 10px 0;
}

.teams-container {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.team-card {
  width: 300px;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 8px;
  background-color: #f9f9f9;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.team-card h3 {
  font-size: 1.2rem;
}

.members {
  margin-top: 20px;
}

.member-card {
  padding: 10px 0;
  border-top: 1px solid #ddd;
}

.member-card p {
  margin: 5px 0;
}

.member-card strong {
  color: #007bff;
}

.add-team-container {
  display: flex;
  justify-content: flex-start;
  margin-bottom: 20px;
}

.add-team-btn {
  padding: 5px 15px; /* Réduit la taille globale */
  font-size: 1.2rem; /* Réduit la taille du symbole "+" */
  color: white;
  background-color: #007bff;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  width: 40px; /* Diminue la largeur */
  height: 40px; /* Diminue la hauteur */
  display: flex;
  justify-content: center;
  align-items: center;
}

.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.popup {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  width: 400px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.popup h3 {
  margin-bottom: 20px;
  text-align: center;
}

.popup form div {
  margin-bottom: 15px;
}

.popup form label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
}

.popup form input,
.popup form textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
}

.popup form button {
  padding: 10px 20px;
  background-color: #4CAF50;
  color: white;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  margin-right: 10px;
}

.popup form button[type="button"] {
  background-color: #e74c3c;
}
</style>

