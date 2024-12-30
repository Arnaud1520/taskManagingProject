<?php

namespace App\Controller;

use App\Entity\Task;
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
        $task->setTitle($data['title'] ?? '');
        $task->setDescription($data['description'] ?? '');
        $task->setPriority($data['priority'] ?? 1);
        $task->setStatus($data['status'] ?? 'toDo');

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

        $task->setTitle($data['title'] ?? $task->getTitle());
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
