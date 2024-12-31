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
          <button class="add-task-btn" @click="showAddForm = true">Ajouter une nouvelle tâche</button>
        </div>

        <!-- Tâches en cours -->
        <div class="tasks-status-section">
          <h3>En cours</h3>
          <div class="tasks-container">
            <div class="task-card" v-for="task in filteredTasks('inProgress')" :key="task.id">
              <i class="edit-icon fas fa-pen" @click="openEditTaskForm(task)"></i>
              <i class="delete-icon fas fa-trash-alt" @click="deleteTask(task.id)"></i>
              <h3>{{ task.name }}</h3>
              <p>{{ task.description }}</p>
              <p><strong>Priorité:</strong> {{ task.priority }}</p>
              <p><strong>Status:</strong> {{ task.status }}</p>
              <p><strong>Équipe:</strong> {{ task.team }}</p>
            </div>
          </div>
        </div>

        <!-- Tâches à faire -->
        <div class="tasks-status-section">
          <h3>À faire</h3>
          <div class="tasks-container">
            <div class="task-card" v-for="task in filteredTasks('toDo')" :key="task.id">
              <i class="edit-icon fas fa-pen" @click="openEditTaskForm(task)"></i>
              <i class="delete-icon fas fa-trash-alt" @click="deleteTask(task.id)"></i>
              <h3>{{ task.name }}</h3>
              <p>{{ task.description }}</p>
              <p><strong>Priorité:</strong> {{ task.priority }}</p>
              <p><strong>Status:</strong> {{ task.status }}</p>
              <p><strong>Équipe:</strong> {{ task.team }}</p>
            </div>
          </div>
        </div>

        <!-- Tâches terminées -->
        <div class="tasks-status-section">
          <h3>Terminées</h3>
          <div class="tasks-container">
            <div class="task-card" v-for="task in filteredTasks('done')" :key="task.id">
              <i class="edit-icon fas fa-pen" @click="openEditTaskForm(task)"></i>
              <i class="delete-icon fas fa-trash-alt" @click="deleteTask(task.id)"></i>
              <h3>{{ task.name }}</h3>
              <p>{{ task.description }}</p>
              <p><strong>Priorité:</strong> {{ task.priority }}</p>
              <p><strong>Status:</strong> {{ task.status }}</p>
              <p><strong>Équipe:</strong> {{ task.team }}</p>
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
            <i class="edit-icon fas fa-pen" @click="openEditTeamForm(team)"></i>
            <i class="delete-icon fas fa-trash-alt" @click="deleteTeam(team.id)"></i>
            <h3>{{ team.name }}</h3>
            <p>{{ team.description }}</p>
            <p><strong>Membres:</strong> 
              <span v-if="team.membersData && team.membersData.length > 0">
                {{ team.membersData.join(', ') }}
              </span>
              <span v-else>Aucun</span>
            </p>

            <button @click="showAddMembersForm(team)">Ajouter des membres</button>
          </div>
        </div>
      </section>

      <!-- Pop-up pour ajouter des membres à une équipe -->
<div v-if="showAddMembersFormVisible" class="popup-overlay" @click.self="showAddMembersFormVisible = false">
  <div class="popup">
    <h3>Ajouter des membres à l'équipe</h3>
    <form @submit.prevent="addMembersToTeam">
      <div>
        <label for="members">Sélectionner un membre :</label>
        <select id="members" v-model="selectedMember" multiple @change="logSelectedMember"> <!-- On retire 'multiple' et on utilise v-model sur selectedMember -->
          <option v-for="user in users" :key="user['@id']" :value="user['@id']">
            {{ user.name }}
          </option>
        </select>
      </div>
      <button type="submit">Ajouter</button>
      <button type="button" @click="showAddMembersFormVisible = false">Annuler</button>
    </form>
  </div>
</div>


      <!-- Pop-up pour ajouter une tâche -->
      <div v-if="showAddForm" class="popup-overlay" @click.self="showAddForm = false">
        <div class="popup">
          <h3>Ajouter une nouvelle tâche</h3>
          <form @submit.prevent="createTask">
            <div>
              <label for="taskName">Titre :</label>
              <input type="text" id="taskName" v-model="newTask.name" required />
            </div>
            <div>
              <label for="taskDescription">Description :</label>
              <textarea id="taskDescription" v-model="newTask.description" required></textarea>
            </div>
            <div>
              <label for="taskPriority">Priorité :</label>
              <input type="number" id="taskPriority" v-model.number="newTask.priority" required />
            </div>
            <div>
              <label for="taskStatus">Statut :</label>
              <select id="taskStatus" v-model="newTask.status" required>
                <option value="toDo">À faire</option>
                <option value="inProgress">En cours</option>
                <option value="done">Terminée</option>
              </select>
            </div>
            <div>
              <label for="taskTeam">Équipe :</label>
              <select id="taskTeam" v-model="newTask.team_id" required @change="logSelectedTask">
                <option v-for="team in teams" :key="team.id" :value="team.id">{{ team.name }}</option>
              </select>
            </div>
            <button type="submit">Ajouter</button>
            <button type="button" @click="showAddForm = false">Annuler</button>
          </form>
        </div>
      </div>

      <!-- Formulaire d'édition d'une équipe -->
      <div v-if="showEditTeamForm" class="popup-overlay" @click.self="showEditTeamForm = false">
        <div class="popup">
          <h3>Modifier l'équipe</h3>
          <form @submit.prevent="updateTeam">
            <div>
              <label for="editTeamName">Nom de l'équipe :</label>
              <input type="text" id="editTeamName" v-model="editTeamData.name" required />
            </div>
            <div>
              <label for="editTeamDescription">Description :</label>
              <textarea id="editTeamDescription" v-model="editTeamData.description" required></textarea>
            </div>
            <button type="submit">Mettre à jour</button>
            <button type="button" @click="showEditTeamForm = false">Annuler</button>
          </form>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  props: ["name"],
  data() {
    return {
      activeSection: "tasks", // Section active par défaut
      showTeamForm: false,
      showAddForm: false,
      showEditForm: false,
      showEditTeamForm: false,
      showAddMembersFormVisible: false, // Propriété ajoutée pour le formulaire d'ajout de membres
      selectedMember: [], // Ajout de la propriété selectedMember
      newTeam: {
        name: "",
        description: "",
        members: [], // Liste des membres à ajouter à l'équipe
      },
      editTeamData: {
        id: null,
        name: "",
        description: "",
        members: [], // Liste des membres de l'équipe
      },
      newTask: {
        name: "",
        description: "",
        priority: 1,
        status: "toDo",
        team_id: null, // Id de l'équipe à laquelle la tâche appartient
      },
      editTaskData: {
        id: null,
        name: "",
        description: "",
        priority: 1,
        status: "toDo",
        team_id: null,
      },
      tasks: [],
      teams: [],  // Initialisation de `teams` comme un tableau vide
      users: [], // Liste des utilisateurs pour les membres d'équipe
      teamMembersData: [], // Tableau pour stocker les détails des membres
    };
  },
  computed: {
    avatarUrl() {
      return `https://api.dicebear.com/6.x/avataaars/svg?seed=${encodeURIComponent(this.name)}`;
    },
  },
  methods: {
    async fetchTasks() {
      try {
        const response = await axios.get("http://localhost:8000/api/tasks");
        this.tasks = response.data.member;
      } catch (error) {
        console.error("Erreur lors de la récupération des tâches:", error);
      }
    },
    async fetchTeams() {
      try {
        const response = await axios.get("http://localhost:8000/api/teams");
        //console.log(response.data);  // Affiche la réponse complète de l'API
        this.teams = response.data.member || [];

        // Vérification de la structure des membres et récupération des données des utilisateurs
        for (let team of this.teams) {
          if (team.members && team.members.length > 0) {
            const memberDataPromises = team.members.map(memberUrl =>
              this.fetchMemberDetails(memberUrl) // Récupère les détails du membre à partir de l'URL
            );
            // Attendre toutes les requêtes des membres
            team.membersData = await Promise.all(memberDataPromises);
          } else {
            team.membersData = [];
          }
        }
      } catch (error) {
        console.error("Erreur lors de la récupération des équipes:", error);
      }
    },
    async fetchMemberDetails(memberUrl) {
      try {
        // Vérifie si l'URL du membre n'est pas vide
        if (memberUrl) {
          const response = await axios.get(memberUrl);
          return response.data.name; // Retourne le nom du membre
        }
      } catch (error) {
        console.error(`Erreur lors de la récupération des détails du membre: ${memberUrl}`, error);
        return null; // Si une erreur survient, retourne null
      }
    },
    async fetchUsers() {
      try {
        const response = await axios.get("http://localhost:8000/api/users");
        this.users = response.data.member; // Utilisez "member" au lieu de "hydra:member"
      } catch (error) {
        console.error("Erreur lors de la récupération des utilisateurs:", error);
      }
    },
    filteredTasks(status) {
      return (this.tasks || []).filter((task) => task.status === status);
    },
    openEditTaskForm(task) {
      this.editTaskData = { ...task };
      this.showEditForm = true;
    },
    openEditTeamForm(team) {
      // Copie des données de l'équipe à éditer
      this.editTeamData = { ...team };
      // Affiche le formulaire d'édition de l'équipe
      this.showEditTeamForm = true;
    },

    async createTask() {
  try {
    // Validation de la priorité
    if (this.newTask.priority < 1 || this.newTask.priority > 5) {
      console.error("La priorité doit être comprise entre 1 et 5.");
      alert("La priorité doit être comprise entre 1 et 5."); // Message pour l'utilisateur
      return;
    }

    // Données de la tâche
    const taskData = {
      name: this.newTask.name,
      description: this.newTask.description,
      priority: this.newTask.priority,
      status: this.newTask.status,
      team: `/api/teams/${this.newTask.team_id}`, // Assure-toi que c'est bien un IRI
    };

    console.log("Données envoyées :", taskData);

    // Requête POST vers l'API
    const response = await axios.post(
      "http://localhost:8000/api/tasks",
      taskData,
      {
        headers: {
          "Content-Type": "application/ld+json",
        },
      }
    );

    console.log("Tâche créée avec succès :", response.data);

    // Réinitialise le formulaire
    this.newTask = {
      name: "",
      description: "",
      priority: 1,
      status: "toDo",
      team_id: null,
    };

    // Ferme le formulaire
    this.showAddForm = false; // Assure-toi que tu as une variable comme `isFormOpen` pour contrôler l'affichage du formulaire
  } catch (error) {
    if (error.response) {
      console.error("Erreur lors de la création de la tâche :", error.response.data);
    } else {
      console.error("Erreur réseau ou autre :", error.message);
    }
  }
},
    
    async updateTask() {
      try {
        // Vérifie que la priorité est bien entre 1 et 5
        if (this.editTaskData.priority < 1 || this.editTaskData.priority > 5) {
          console.error("La priorité doit être entre 1 et 5.");
          return;
        }

        await axios.put(
          `http://localhost:8000/api/tasks/${this.editTaskData.id}`,
          this.editTaskData
        );
        const index = this.tasks.findIndex((task) => task.id === this.editTaskData.id);
        if (index !== -1) {
          this.tasks[index] = { ...this.editTaskData };
        }
        this.showEditForm = false;
      } catch (error) {
        console.error("Erreur lors de la mise à jour de la tâche:", error);
      }
    },
    async deleteTask(id) {
      try {
        await axios.delete(`http://localhost:8000/api/tasks/${id}`);
        this.tasks = this.tasks.filter((task) => task.id !== id);
      } catch (error) {
        console.error("Erreur lors de la suppression de la tâche:", error);
      }
    },
    async createTeam() {
      if (!this.newTeam.name.trim()) {
        console.error("Le nom de l'équipe est requis.");
        return;
      }

      try {
        const response = await axios.post(
          "http://localhost:8000/api/teams",
          this.newTeam,
          {
            headers: {
              "Content-Type": "application/ld+json",
            },
          }
        );
        console.log("Équipe créée avec succès :", response.data);

        // Rafraîchir la liste des équipes pour refléter la nouvelle équipe
        await this.fetchTeams();

        this.resetTeamForm(); // Réinitialiser le formulaire
        this.showTeamForm = false; // Fermer le popup
      } catch (error) {
        console.error("Erreur lors de la création de l'équipe :", error.response || error);
      }
    },
    async updateTeam() {
      try {
        await axios.put(
          `http://localhost:8000/api/teams/${this.editTeamData.id}`,
          this.editTeamData
        );
        const index = this.teams.findIndex((team) => team.id === this.editTeamData.id);
        if (index !== -1) {
          this.teams[index] = { ...this.editTeamData };
        }
        this.showEditTeamForm = false;
      } catch (error) {
    // Afficher l'erreur complète
    if (error.response) {
      console.error("Erreur lors de la mise à jour de l'équipe:", error.response.status);
      console.error("Détails de l'erreur:", error.response.data);
    } else if (error.request) {
      console.error("Aucune réponse du serveur:", error.request);
    } else {
      console.error("Erreur:", error.message);
    }
  }
    },
    async deleteTeam(id) {
      try {
        await axios.delete(`http://localhost:8000/api/teams/${id}`);
        this.teams = this.teams.filter((team) => team.id !== id);
      } catch (error) {
        console.error("Erreur lors de la suppression de l'équipe:", error);
      }
    },
    async addMembersToTeam() {
  try {
    // Vérifie que l'ID de l'équipe est bien défini
    if (!this.editTeamData.id) {
      console.error("ID de l'équipe non trouvé.");
      return;
    }

    // Vérifie si des membres ont été sélectionnés
    if (!Array.isArray(this.selectedMember) || this.selectedMember.length === 0) {
      console.error("Aucun membre sélectionné.");
      return;
    }

    // Log des membres sélectionnés avant l'extraction des IDs
    //console.log("Membres sélectionnés avant extraction des IDs : ", this.selectedMember);

    // Mappe les URI pour extraire uniquement les IDs
    const memberIds = this.selectedMember.map(memberUri => {
      // Extrait l'ID de l'URI '/api/users/{id}'
      const id = memberUri.split('/').pop();
      return id;
    });

    console.log("IDs des membres sélectionnés : ", memberIds);

    // Envoi de la requête pour chaque utilisateur sélectionné
    for (const memberId of memberIds) {
      console.log(`Envoi de la requête pour l'utilisateur avec l'ID : ${memberId}`);

      const response = await axios.post(
        `http://localhost:8000/api/teams/${this.editTeamData.id}/add_member`,
        { user_id: memberId },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      );

      console.log(`Membre ajouté : ${memberId}`, response.data);
    }

    // Ferme la fenêtre et recharge les équipes
    this.showAddMembersFormVisible = false;
    await this.fetchTeams();
  } catch (error) {
    console.error("Erreur lors de l'ajout des membres à l'équipe :", error.response || error);
  }
},
    resetTaskForm() {
      this.newTask = {
        name: "",
        description: "",
        priority: 1,
        status: "toDo",
        team_id: null,
      };
    },
    resetTeamForm() {
      this.newTeam = {
        name: "",
        description: "",
        members: [], // Réinitialisation de la liste des membres
      };
    },
    // Nouvelle méthode pour afficher le formulaire d'ajout de membres
    showAddMembersForm(team) {
      this.showAddMembersFormVisible = true; // Affiche le formulaire
      this.editTeamData = { ...team }; // Assure que l'ID de l'équipe est correctement défini
    },
    logSelectedMember() {
  console.log("Membres sélectionnés :", JSON.parse(JSON.stringify(this.selectedMember)));
  },
    logSelectedTask() {
  console.log("Team sélectionnée :", JSON.parse(JSON.stringify(this.newTask.team_id)));
  },
  },
  async mounted() {
    //console.log("Liste des utilisateurs :", this.users);
    await this.fetchTasks();
    await this.fetchTeams();
    await this.fetchUsers(); // Récupère les utilisateurs pour gérer les membres d'équipe
  },
};
</script>







<style scoped>
.dashboard-page {
  display: flex;
  height: 100vh;
  background-color: #ecf0f1;
}

.sidebar {
  width: 250px;
  background-color: #2c3e50;
  color: white;
  padding: 20px;
  position: fixed;
  height: 100vh;
}

.profile-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  margin-top: 50px;
}

.avatar {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  margin-bottom: 10px;
  border: 3px solid #16a085;
}

.username {
  font-size: 1.4rem;
  font-weight: bold;
  color: white;
  text-align: center;
  margin-top: 10px;
}

.sidebar ul {
  list-style-type: none;
  padding: 0;
}

.sidebar ul li {
  padding: 12px;
  cursor: pointer;
  border-bottom: 1px solid #34495e;
  transition: background-color 0.3s ease;
}

.sidebar ul li:hover {
  background-color: #34495e;
}

.sidebar ul li.active {
  background-color: #16a085;
}

.content {
  margin-left: 250px;
  padding: 40px 20px;
  width: 100%;
  overflow-y: auto;
}

h1 {
  text-align: center;
  margin-bottom: 40px;
  font-size: 2rem;
}

.tasks-section,
.teams-section {
  margin-bottom: 50px;
}

.add-task-btn,
.add-team-btn {
  display: inline-flex;
  justify-content: center;
  align-items: center;
  padding: 12px 22px;
  background-color: #4caf50;
  color: white;
  border: none;
  cursor: pointer;
  font-size: 1rem;
  border-radius: 8px;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.add-task-btn:hover,
.add-team-btn:hover {
  background-color: #45a049;
  transform: scale(1.05);
}

.tasks-container,
.teams-container {
  display: flex;
  flex-wrap: wrap;
  gap: 25px;
}

.task-card,
.team-card {
  position: relative;
  width: 320px;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 10px;
  background-color: #fff;
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  transition: box-shadow 0.3s ease, transform 0.2s ease;
}

.task-card:hover,
.team-card:hover {
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
  transform: translateY(-5px);
}

.task-card h3,
.team-card h3 {
  font-size: 1.3rem;
  color: #34495e;
}

.task-card p,
.team-card p {
  margin: 10px 0;
  color: #7f8c8d;
}

.members {
  margin-top: 20px;
}

.member-card {
  padding: 12px 0;
  border-top: 1px solid #ddd;
}

.member-card p {
  margin: 5px 0;
}

.member-card strong {
  color: #007bff;
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
  z-index: 1000;
}

.popup {
  background-color: white;
  padding: 25px;
  border-radius: 12px;
  width: 420px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  animation: fadeIn 0.3s ease;
}

.popup h3 {
  margin-bottom: 20px;
  text-align: center;
  font-size: 1.5rem;
}

.popup form div {
  margin-bottom: 20px;
}

.popup form label {
  display: block;
  font-weight: bold;
  margin-bottom: 8px;
}

.popup form input,
.popup form textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 8px;
}

.popup form button {
  padding: 12px 24px;
  background-color: #4caf50;
  color: white;
  border: none;
  cursor: pointer;
  border-radius: 8px;
  transition: transform 0.2s ease, background-color 0.3s ease;
}

.popup form button:hover {
  background-color: #45a049;
  transform: scale(1.05);
}

.popup form button[type="button"] {
  background-color: #e74c3c;
}

.popup form button[type="button"]:hover {
  background-color: #c0392b;
}

.edit-icon {
  position: absolute;
  top: 15px;
  right: 15px;
  width: 22px;
  height: 22px;
  cursor: pointer;
  color: #007bff;
  transition: transform 0.2s ease, color 0.3s ease;
}

.edit-icon:hover {
  transform: scale(1.2);
  color: #0056b3;
}

.delete-icon {
  position: absolute;
  top: 15px;
  right: 50px;
  width: 22px;
  height: 22px;
  cursor: pointer;
  color: #e74c3c;
  transition: transform 0.2s ease, color 0.3s ease;
}

.delete-icon:hover {
  transform: scale(1.2);
  color: #c0392b;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>




