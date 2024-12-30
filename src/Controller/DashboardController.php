<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/api/dashboard', name: 'api_dashboard', methods: ['GET'])]
    public function dashboard(TaskRepository $taskRepository, TeamRepository $teamRepository): JsonResponse
    {
        $user = $this->getUser();  // Récupérer l'utilisateur connecté grâce à la session

        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié.'], 401);
        }

        // Récupérer les équipes de l'utilisateur
        $teams = $teamRepository->findByUser($user);  // Assurez-vous que cette méthode existe

        // Récupérer les tâches de chaque équipe
        $tasks = [];
        foreach ($teams as $team) {
            $teamTasks = $taskRepository->findBy(['team' => $team]);
            foreach ($teamTasks as $task) {
                $tasks[] = [
                    'id' => $task->getId(),
                    'title' => $task->getTitle(),
                    'description' => $task->getDescription(),
                    'status' => $task->getStatus(),
                    'priority' => $task->getPriority(),
                    'team' => $team->getName(),
                ];
            }
        }

        return new JsonResponse([
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
            ],
            'teams' => array_map(fn($team) => $team->getName(), $teams),
            'tasks' => $tasks
        ]);
    }
}
