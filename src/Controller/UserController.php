<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/api/register", name="api_register", methods={"POST"})
     */
    public function register(Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Récupérer les données envoyées par Vue.js (format JSON)
        $data = json_decode($request->getContent(), true);

        // Valider les données
        if (!isset($data['name'], $data['email'], $data['password'], $data['phone'])) {
            return new JsonResponse(['error' => 'Missing data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Créer un nouvel utilisateur
        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));  // Hash le mot de passe
        $user->setPhone($data['phone']);
        $user->setRoles(['ROLE_USER']);  // Par défaut, l'utilisateur est un utilisateur normal

        // Sauvegarder l'utilisateur en base de données
        $em->persist($user);
        $em->flush();

        return new JsonResponse(['message' => 'User created successfully'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/api/users', methods: ['GET'], name: 'get_users')]
    public function getUsers(UserRepository $userRepository): JsonResponse
    {
        // Récupérer la liste des utilisateurs
        $users = $userRepository->findAll();

        // Retourner les utilisateurs au format JSON
        return new JsonResponse($users);
    }
}
