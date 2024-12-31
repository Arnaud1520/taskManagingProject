<?php

// src/Controller/TeamController.php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\User;  // Assurez-vous d'importer l'entité User
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface; // Importer l'EntityManagerInterface
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class TeamController extends AbstractController
{
    private $entityManager;

    // Injection de l'EntityManagerInterface dans le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/teams', name: 'api_teams_create', methods: ['POST'])]
    public function createTeam(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['message' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }

        // Créer une nouvelle équipe
        $team = new Team();

        if (empty($data['name']) || empty($data['description'])) {
            return new JsonResponse(['message' => 'Les champs name et description sont obligatoires.'], Response::HTTP_BAD_REQUEST);
        }
        
        $team->setName($data['name']);
        $team->setDescription($data['description']);

        // Enregistrer l'équipe dans la base de données
        $this->entityManager->persist($team);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Équipe créée avec succès',
            'team' => [
                'id' => $team->getId(),
                'name' => $team->getName(),
                'description' => $team->getDescription(),
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('/api/teams/{id}', name: 'api_teams_show', methods: ['GET'])]
    public function getTeam(Team $team): JsonResponse
    {
        if (!$team) {
            return new JsonResponse(['message' => 'Équipe non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $membersData = $this->getMembersData($team);

        return new JsonResponse([
            'id' => $team->getId(),
            'name' => $team->getName(),
            'description' => $team->getDescription(),
            'members' => $membersData,
        ]);
    }

    #[Route('/api/teams/{id}/add_member', name: 'api_teams_add_member', methods: ['POST'])]
    public function addMemberToTeam(Request $request, Team $team): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['user_id'])) {
            return new JsonResponse(['message' => 'L\'ID de l\'utilisateur est obligatoire.'], Response::HTTP_BAD_REQUEST);
        }

        $userId = $data['user_id'];

        // Récupérer l'utilisateur par son ID
        $user = $this->entityManager->getRepository(User::class)->find($userId);
        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        // Ajouter l'utilisateur à l'équipe
        $team->addMember($user);  // Assurez-vous que la méthode `addMember()` existe dans l'entité Team
        $this->entityManager->flush();

        // Récupérer les membres mis à jour
        $membersData = $this->getMembersData($team);

        return new JsonResponse([
            'message' => 'Membre ajouté avec succès',
            'team' => [
                'id' => $team->getId(),
                'name' => $team->getName(),
                'description' => $team->getDescription(),
                'members' => $membersData,
            ]
        ]);
    }

    private function getMembersData(Team $team): array
    {
        $members = $team->getMembers();  // Assurez-vous que cette méthode retourne les membres
        $membersData = [];
        foreach ($members as $member) {
            $membersData[] = [
                'id' => $member->getId(),
                'name' => $member->getName(),
                'email' => $member->getEmail(),
            ];
        }
        return $membersData;
    }

    #[Route('/api/teams/{id}', name: 'api_teams_update', methods: ['PUT'])]
    public function updateTeam(Request $request, Team $team): JsonResponse
    {
        if (!$team) {
            return new JsonResponse(['message' => 'Équipe non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $team->setName($data['name']);
        }
        if (isset($data['description'])) {
            $team->setDescription($data['description']);
        }

        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Équipe mise à jour avec succès',
            'team' => [
                'id' => $team->getId(),
                'name' => $team->getName(),
                'description' => $team->getDescription(),
            ]
        ]);
    }

    #[Route('/api/teams/{id}', name: 'api_teams_delete', methods: ['DELETE'])]
    public function deleteTeam(Team $team): JsonResponse
    {
        if (!$team) {
            return new JsonResponse(['message' => 'Équipe non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($team);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Équipe supprimée avec succès'], Response::HTTP_NO_CONTENT);
    }
}
