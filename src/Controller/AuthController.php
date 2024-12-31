<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        try {
            // Décoder les données JSON
            $data = json_decode($request->getContent(), true);

            if (!$data) {
                return new JsonResponse(['message' => 'Invalid data.'], 400);
            }

            // Vérification des champs requis
            if (empty($data['email']) || empty($data['password'])) {
                return new JsonResponse(['message' => 'Tous les champs sont obligatoires.'], 400);
            }

            // Recherche de l'utilisateur par email
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);

            if (!$user) {
                return new JsonResponse(['message' => 'Utilisateur non trouvé.'], 404);
            }

            // Vérification du mot de passe
            if (!$passwordHasher->isPasswordValid($user, $data['password'])) {
                return new JsonResponse(['message' => 'Mot de passe incorrect.'], 401);
            }

            // Réponse de succès (ici, sans token pour l'instant)
            return new JsonResponse([
                'message' => 'Connexion réussie.',
                'user' => [
                    'id' => $user->getId(),
                    'email' => $user->getEmail(),
                    'name' => $user->getName(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Erreur serveur : ' . $e->getMessage()], 500);
        }
    }
}
