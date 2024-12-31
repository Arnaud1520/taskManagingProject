<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Team; // Assurez-vous d'importer l'entité Team
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    #[Route('/api/tasks', methods: ['GET'], name: 'get_tasks')]
    public function getTasks(TaskRepository $taskRepository): JsonResponse
    {
        $tasks = $taskRepository->findAll();

        return $this->json($tasks, 200, [], ['groups' => 'task:read']);
    }

    #[Route('/api/tasks/{id}', methods: ['GET'], name: 'get_task')]
    public function getTask(int $id, TaskRepository $taskRepository): JsonResponse
    {
        $task = $taskRepository->find($id);

        if (!$task) {
            return $this->json(['error' => 'Task not found'], 404);
        }

        return $this->json($task, 200, [], ['groups' => 'task:read']);
    }

    #[Route('/api/tasks', methods: ['POST'], name: 'create_task')]
public function createTask(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    $task = new Task();
    $task->setName($data['name'] ?? ''); 
    $task->setDescription($data['description'] ?? '');
    $task->setPriority($data['priority'] ?? 1);
    $task->setStatus($data['status'] ?? 'toDo');

    // Vérifier si l'ID de l'équipe est fourni et récupérer l'entité Team
    if (isset($data['team_id'])) {
        $team = $entityManager->getRepository(Team::class)->find($data['team_id']);
        
        if ($team) {
            $task->setTeam($team); // Assigner l'équipe à la tâche
        } else {
            return $this->json(['error' => 'Team not found'], 404);
        }
    } else {
        return $this->json(['error' => 'Team ID is required'], 400);
    }

    // Persister la tâche dans la base de données
    $entityManager->persist($task);
    $entityManager->flush();

    return $this->json($task, 201, [], ['groups' => 'task:read']);
}


    #[Route('/api/tasks/{id}', methods: ['PUT'], name: 'update_task')]
    public function updateTask(int $id, Request $request, TaskRepository $taskRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $task = $taskRepository->find($id);

        if (!$task) {
            return $this->json(['error' => 'Task not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $task->setName($data['name'] ?? $task->getName()); // Utiliser "name"
        $task->setDescription($data['description'] ?? $task->getDescription());
        $task->setPriority($data['priority'] ?? $task->getPriority());
        $task->setStatus($data['status'] ?? $task->getStatus());

        $entityManager->flush();

        return $this->json($task, 200, [], ['groups' => 'task:read']);
    }

    #[Route('/api/tasks/{id}', methods: ['DELETE'], name: 'delete_task')]
    public function deleteTask(int $id, TaskRepository $taskRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $task = $taskRepository->find($id);

        if (!$task) {
            return $this->json(['error' => 'Task not found'], 404);
        }

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->json(['message' => 'Task deleted'], 204);
    }
}
