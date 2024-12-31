# Task Management API

Ce projet est une API de gestion des tâches développée avec Symfony et API Platform. L'API permet aux utilisateurs de créer, récupérer et gérer des tâches, en associant chaque tâche à un utilisateur spécifique. Les tâches peuvent avoir une priorité, un statut, une description et peuvent être liées à d'autres tâches en tant que dépendances.

Fonctionnalités principales :
Créer une tâche : L'utilisateur peut créer une tâche en spécifiant son titre, description, priorité, et statut.
Association à un utilisateur : Chaque tâche est associée à un utilisateur via l'ID de l'utilisateur.
Gestion des dépendances : Les tâches peuvent avoir des dépendances avec d'autres tâches (c'est-à-dire que certaines tâches doivent être terminées avant d'autres).
API REST : L'API est accessible via les routes REST et permet des opérations POST, GET, et PUT.



Si vous rencontrez des problèmes de chargement des données, notamment après avoir effectué des changements dans l'API ou l'interface, il est possible que des problèmes de cache se produisent dans votre navigateur.

Solution :
Essayez de lancer l'application en mode navigation privée. Cela permet de contourner les problèmes de cache et d'éviter que votre navigateur n'utilise une version obsolète des fichiers JavaScript ou des données stockées en cache.Si jamais le bouton
de déconnexion n'est pas visible il faut refresh  la page 

## Recommended IDE Setup

[VSCode](https://code.visualstudio.com/) + [Volar](https://marketplace.visualstudio.com/items?itemName=Vue.volar) (and disable Vetur).

## Customize configuration

See [Vite Configuration Reference](https://vite.dev/config/).

## Project Setup

```sh
npm install
```

### Compile and Hot-Reload for Development

```sh
npm run dev
```

### Compile and Minify for Production

```sh
npm run build
```

### Lint with [ESLint](https://eslint.org/)

```sh
npm run lint
```
