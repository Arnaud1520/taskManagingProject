<?php

// Exemple d'accès aux informations de l'utilisateur dans le contrôleur du dashboard
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/api/dashboard', name: 'api_dashboard', methods: ['GET'])]
    public function dashboard(): JsonResponse
    {
        $user = $this->getUser();  // Récupérer l'utilisateur connecté grâce à la session

        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié.'], 401);
        }

        return new JsonResponse([
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
            ]
        ]);
    }
}

