<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType; // Si tu utilises un formulaire
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

class TeamController extends AbstractController
{
    #[Route('/api/teams', name: 'api_teams_create', methods: ['POST'])]
    public function createTeam(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true); // Récupérer les données envoyées en POST

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
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($team);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Équipe créée avec succès',
            'team' => [
                'id' => $team->getId(),
                'name' => $team->getName(),
                'description' => $team->getDescription(),
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('/api/teams', name: 'api_teams_list', methods: ['GET'])]
    public function getTeams(TeamRepository $teamRepository): JsonResponse
    {
        $teams = $teamRepository->findAll();

        $teamsData = [];
        foreach ($teams as $team) {
            $teamsData[] = [
                'id' => $team->getId(),
                'name' => $team->getName(),
                'description' => $team->getDescription(),
            ];
        }

        return new JsonResponse(['teams' => $teamsData]);
    }

    #[Route('/api/teams/{id}', name: 'api_teams_show', methods: ['GET'])]
    public function getTeam(Team $team): JsonResponse
    {
        if (!$team) {
            return new JsonResponse(['message' => 'Équipe non trouvée'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $team->getId(),
            'name' => $team->getName(),
            'description' => $team->getDescription(),
        ]);
    }

    #[Route('/api/teams/{id}', name: 'api_teams_update', methods: ['PUT'])]
    public function updateTeam(Request $request, Team $team): JsonResponse
    {
        if (!$team) {
            return new JsonResponse(['message' => 'Équipe non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true); // Récupérer les données envoyées en PUT

        if (isset($data['name'])) {
            $team->setName($data['name']);
        }
        if (isset($data['description'])) {
            $team->setDescription($data['description']);
        }

        // Enregistrer les modifications dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

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

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($team);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Équipe supprimée avec succès'], Response::HTTP_NO_CONTENT);
    }
}

