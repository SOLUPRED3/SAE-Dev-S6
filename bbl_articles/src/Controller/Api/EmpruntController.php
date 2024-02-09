<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EmpruntRepository;
use App\Entity\Emprunt;
use App\Repository\AdherentRepository;
use App\Entity\Adherent;
use App\Repository\LivreRepository;
use App\Entity\Livre;

class EmpruntController extends AbstractController
{
    #[Route('/api/emprunt', name: 'app_api_emprunt', methods: ['GET'])]
    public function index(Request $request, EmpruntRepository $empruntRepository ,LivreRepository $livreRepository, AdherentRepository $adherentRepository): JsonResponse
    {
        $dateEmprunt = $request->query->get('dateEmprunt');

        $dateRetour = $request->query->get('dateRetour');

        $emprunteurName = $request->query->get('emprunteur');

        $livreName = $request->query->get('livre');

        $sort = $request->query->get('sort');
        $sort = explode(',', $sort);

        if($responseSize !== null && $responseOffset !== null){
            $data = array_slice($data, $responseOffset, $responseSize);
        }

        $emprunts = $empruntRepository->findAll();
        $data = [];

        foreach($emprunts as $emprunts){
            if(
                (
                    $dateEmprunt == null ||
                    $dateEmprunt == $emprunts->getDateEmprunt()->format('d/m/Y')
                ) &&
                (
                    $dateRetour == null ||
                    $dateRetour == $emprunts->getDateRetour()->format('d/m/Y')
                ) &&
                preg_match('/'.$livreName.'/i', $emprunts->getLivre()->getTitre()) &&
                preg_match('/'.$emprunteurName.'/i', $emprunts->getEmprunteur()->getNom())
            ){
                
                $emprunteurId = $emprunts->getEmprunteur();
                $emprunteur = $adherentRepository->find($emprunteurId);
                
                $livreId = $emprunts->getLivre();
                $livre = $livreRepository->find($livreId);
                
                $data[] = [
                    'id' => $emprunts->getId(),
                    'dateEmprunt' => $emprunts->getDateEmprunt(),
                    'dateRetour' => $emprunts->getDateRetour(),
                    'emprunteur' => $emprunteur ? $emprunteur->getNom() : null,
                    'livre' => $livre ? $livre->getTitre() : null,
                ];
            }
        }
        if(empty($data)){
            return new JsonResponse(['message' => 'Emprunt not found'], 404);
        }

        if($responseSize !== null && $responseOffset !== null){
            $data = array_slice($data, $responseOffset, $responseSize);
        }

        if(count($sort) == 2 && array_key_exists($sort[0], $data[0]) && in_array($sort[1], ['asc', 'desc'])){
            foreach ($data as $key => $row) {
                if (isset($row[$sort[0]])) {
                    $sortColumn[$key] = $row[$sort[0]];
                    $order[$key] = $row[$sort[0]];
                }
            }
            
            array_multisort($sortColumn, ($sort[1] === 'desc' ? SORT_DESC : SORT_ASC), $data);
        }

        return $this->json($data);
    }

    #[Route('/api/emprunt/{id}', name: 'app_api_emprunt_get', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function getById(int $id, EmpruntRepository $empruntRepository, LivreRepository $livreRepository, AdherentRepository $adherentRepository): JsonResponse
    {
        $emprunt = $empruntRepository->find($id);
        if ($emprunt === null) {
            return new JsonResponse(['message' => 'Emprunt not found'], 404);
        }

        $dateEmprunt = $emprunt->getDateEmprunt();
        $dateRetour = $emprunt->getDateRetour();

        $emprunteurId = $emprunt->getEmprunteur();
        $emprunteur = $adherentRepository->find($emprunteurId);

        $livreId = $emprunt->getLivre();
        $livre = $livreRepository->find($livreId);

        $data = [
            'id' => $id,
            'dateEmprunt' => $dateEmprunt,
            'dateRetour' => $dateRetour,
            'emprunteur' => $emprunteur ? $emprunteur->getNom() : null,
            'livre' => $livre ? $livre->getTitre() : null,
        ];

        return $this->json($data);
    }

    #[Route('/api/emprunt', name: 'app_api_emprunt_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, LivreRepository $livreRepository, AdherentRepository $adherentRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Récupérer les données de l'emprunt
        $dateEmprunt = $data['dateEmprunt'] ?? null;
        $dateRetour = $data['dateRetour'] ?? null;
        $emprunteurId = $data['emprunteurId'] ?? null;
        $livreId = $data['livreId'] ?? null;

        // Vérifier si les données requises sont présentes
        if (!$dateEmprunt || !$dateRetour || !$emprunteurId || !$livreId) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        // Convertir les dates en objets DateTime
        $dateEmprunt = \DateTime::createFromFormat('d/m/Y', $dateEmprunt);
        $dateRetour = \DateTime::createFromFormat('d/m/Y', $dateRetour);

        // Vérifier si les dates sont valides
        if (!$dateEmprunt || !$dateRetour) {
            return new JsonResponse(['error' => 'Invalid date format. Please use dd/mm/yyyy format.'], 400);
        }

        // Récupérer l'emprunteur et le livre
        $emprunteur = $adherentRepository->find($emprunteurId);
        $livre = $livreRepository->find($livreId);

        // Vérifier si l'emprunteur et le livre existent
        if (!$emprunteur || !$livre) {
            return new JsonResponse(['error' => 'Emprunteur or Livre not found'], 404);
        }

        // Créer un nouvel objet Emprunt
        $emprunt = new Emprunt();
        $emprunt->setDateEmprunt($dateEmprunt);
        $emprunt->setDateRetour($dateRetour);
        $emprunt->setEmprunteur($emprunteur);
        $emprunt->setLivre($livre);

        // Enregistrer l'emprunt en base de données
        $entityManager->persist($emprunt);
        $entityManager->flush();

        // Retourner la réponse avec l'identifiant de l'emprunt créé
        return new JsonResponse(['message' => 'Emprunt created', 'id' => $emprunt->getId()], 201);
    }

    #[Route('/api/emprunt/{id}', name: 'app_api_emprunt_update', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $entityManager, EmpruntRepository $empruntRepository, LivreRepository $livreRepository, AdherentRepository $adherentRepository, int $id): JsonResponse
    {
        $emprunt = $empruntRepository->find($id);

        // Vérifier si l'emprunt existe
        if (!$emprunt) {
            return new JsonResponse(['error' => 'Emprunt not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        // Récupérer les données de l'emprunt
        $dateEmprunt = $data['dateEmprunt'] ?? null;
        $dateRetour = $data['dateRetour'] ?? null;
        $emprunteurId = $data['emprunteurId'] ?? null;
        $livreId = $data['livreId'] ?? null;

        // Vérifier si les données requises sont présentes
        if (!$dateEmprunt || !$dateRetour || !$emprunteurId || !$livreId) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        // Convertir les dates en objets DateTime
        $dateEmprunt = \DateTime::createFromFormat('d/m/Y', $dateEmprunt);
        $dateRetour = \DateTime::createFromFormat('d/m/Y', $dateRetour);

        // Vérifier si les dates sont valides
        if (!$dateEmprunt || !$dateRetour) {
            return new JsonResponse(['error' => 'Invalid date format. Please use dd/mm/yyyy format.'], 400);
        }

        // Récupérer l'emprunteur et le livre
        $emprunteur = $adherentRepository->find($emprunteurId);
        $livre = $livreRepository->find($livreId);

        // Vérifier si l'emprunteur et le livre existent
        if (!$emprunteur) {
            return new JsonResponse(['error' => 'Emprunteur not found'], 404);
        }
        if (!$livre) {
            return new JsonResponse(['error' => 'Livre not found'], 404);
        }

        // Mettre à jour les données de l'emprunt
        $emprunt->setDateEmprunt($dateEmprunt);
        $emprunt->setDateRetour($dateRetour);
        $emprunt->setEmprunteur($emprunteur);
        $emprunt->setLivre($livre);

        // Enregistrer les modifications en base de données
        $entityManager->flush();

        // Retourner la réponse avec l'identifiant de l'emprunt mis à jour
        return new JsonResponse(['message' => 'Emprunt updated', 'id' => $emprunt->getId()], 200);
    }

    #[Route('/api/emprunt/{id}', name: 'app_api_emprunt_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, EmpruntRepository $empruntRepository, int $id): JsonResponse
    {
        $emprunt = $empruntRepository->find($id);
    
        // Vérifier si l'emprunt existe
        if (!$emprunt) {
            return new JsonResponse(['error' => 'Emprunt not found'], 404);
        }
    
        // Supprimer l'emprunt de la base de données
        $entityManager->remove($emprunt);
        $entityManager->flush();
    
        // Retourner la réponse indiquant que l'emprunt a été supprimé avec succès
        return new JsonResponse(['message' => 'Emprunt deleted'], 200);
    }

}