<?php

namespace App\EventListener;

use App\Entity\Task;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

class TaskEventListener
{
    private $security;
    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        
        // Vérifiez si l'élément est une création de tâche (POST sur /tasks)
        if ($request->getPathInfo() === '/tasks' && $request->isMethod('POST')) {
            // Vérifier l'utilisateur authentifié
            $user = $this->security->getUser();

            if ($user instanceof \App\Entity\User) {
                // Récupérer l'entité Task
                $task = $event->getRequest()->getContent();
                $task = json_decode($task, true);

                // Ajoutez l'utilisateur à la tâche
                $task['user'] = $user->getId();

                // Appliquez les modifications à la tâche avant d'enregistrer
                $event->getRequest()->request->set('user', $task['user']);
            }
        }
    }
}
