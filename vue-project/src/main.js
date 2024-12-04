import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import './assets/buttons.css';


const app = createApp(App);

app.use(router); // Ajoutez le routeur à l'application Vue
app.mount('#app');
