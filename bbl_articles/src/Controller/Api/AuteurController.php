<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AuteurRepository;
use App\Entity\Auteur;
use App\Repository\LivreRepository;
use App\Entity\Livre;


class AuteurController extends AbstractController
{
    #[Route('/api/auteur', name: 'app_api_auteur', methods: ['GET'])]
    public function index(AuteurRepository $auteurRepository, LivreRepository $livreRepository): JsonResponse
    {
        $auteurs = $auteurRepository->findAll();
        $data = [];

        foreach ($auteurs as $auteur) {
            $livreIds = $auteur->getLivres();
            $livres = [];
            foreach ($livreIds as $livreId) {
                $livre = $livreRepository->find($livreId);
                if ($livre) {
                    $livres[] = $livre->getTitre();
                }
            }

            $livresString = implode(', ', $livres);

            $data[] = [
                 'id' => $auteur->getId(),
                 'nom' => $auteur->getNom(),
                 'prenom' => $auteur->getPrenom(),
                 'dateNaiss' => $auteur->getDateNaiss(),
                 'dateDeces' => $auteur->getDateDeces(),
                 'nationalite' => $auteur->getNationalite(),
                 'photo' => $auteur->getPhoto(),
                 'description' => $auteur->getDescription(),
                 'livres' => $livresString,
            ];
            
        }
        return $this->json($data);
    }

    #[Route('/api/auteur/{id}', name: 'app_api_auteur_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(AuteurRepository $auteurRepository, LivreRepository $livreRepository, int $id): JsonResponse
    {
        $auteur = $auteurRepository->find($id);
        if ($auteur === null) {
            return $this->json(['message' => 'Auteur not found'], 404);
        }

        $livreIds = $auteur->getLivres();
        $livres = [];
        foreach ($livreIds as $livreId) {
            $livre = $livreRepository->find($livreId);
            if ($livre) {
                $livres[] = $livre->getTitre();
            }
        }

        $livresString = implode(', ', $livres);

        $data = [
            'id' => $auteur->getId(),
            'nom' => $auteur->getNom(),
            'prenom' => $auteur->getPrenom(),
            'dateNaiss' => $auteur->getDateNaiss(),
            'dateDeces' => $auteur->getDateDeces(),
            'nationalite' => $auteur->getNationalite(),
            'photo' => $auteur->getPhoto(),
            'description' => $auteur->getDescription(),
            'livres' => $livresString,
        ];
        return $this->json($data);
    }

    #[Route('/api/auteur/{id}', name: 'app_api_auteur_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(AuteurRepository $auteurRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $auteur = $auteurRepository->find($id);
        if ($auteur === null) {
            return $this->json(['message' => 'Auteur not found'], 404);
        }
    
        $entityManager->remove($auteur);
        $entityManager->flush();
    
        return $this->json(['message' => 'Auteur deleted'], 200);
    }

    #[Route('/api/auteur/{id}', name: 'app_api_auteur_update', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(Request $request, AuteurRepository $auteurRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $auteur = $auteurRepository->find($id);
        if ($auteur === null) {
            return $this->json(['message' => 'Auteur not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $nom = $data['nom'] ?? null;
        $prenom = $data['prenom'] ?? null;
        $dateNaiss = $data['dateNaiss'] ?? null;
        $dateDeces = $data['dateDeces'] ?? null;
        $nationalite = $data['nationalite'] ?? null;
        $photo = $data['photo'] ?? null;
        $description = $data['description'] ?? null;
        $livreIds = $data['livres'] ?? [];

        foreach($livreIds as $livreId){
            $livre = $livreRepository->find($livreId);
            if($livre){
                $auteur->addLivre($livre);
            }
        }

        if ($nom !== null) {
            $auteur->setNom($nom);
        }
        if ($prenom !== null) {
            $auteur->setPrenom($prenom);
        }
        if ($dateNaiss !== null) {
            // Convertir la date de naissance en objet DateTime
            $dateNaiss = \DateTime::createFromFormat('d/m/Y', $dateNaiss);
            $auteur->setDateNaiss($dateNaiss);
        }
        if ($dateDeces !== null) {
            // Convertir la date de décès en objet DateTime
            $dateDeces = \DateTime::createFromFormat('d/m/Y', $dateDeces);
            $auteur->setDateDeces($dateDeces);
        }
        if ($nationalite !== null) {
            $auteur->setNationalite($nationalite);
        }
        if ($photo !== null) {
            $auteur->setPhoto($photo);
        }
        if ($description !== null) {
            $auteur->setDescription($description);
        }

        $entityManager->flush();

        return $this->json(['message' => 'Auteur updated', 'id' => $auteur->getId()], 201);
    }

    #[Route('/api/auteur', name: 'app_api_auteur_post', methods: ['POST'])]
    public function create(Request $request, LivreRepository $livreRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer les données du corps de la requête
        $data = json_decode($request->getContent(), true);
    
        // Vérifier si les données sont présentes et non vides
        if (!empty($data)) {
            // Créer une nouvelle instance de l'entité Auteur
            $auteur = new Auteur();
    
            // Remplir les propriétés de l'auteur avec les données reçues
            $auteur->setNom($data['nom'] ?? null);
            $auteur->setPrenom($data['prenom'] ?? null);
            if (isset($data['dateNaiss'])) {
                // Convertir la date de naissance en objet DateTimeImmutable
                $dateNaiss = \DateTimeImmutable::createFromFormat('d/m/Y', $data['dateNaiss']);
                $auteur->setDateNaiss($dateNaiss);
            }
            
            if (isset($data['dateDeces'])) {
                // Convertir la date de décès en objet DateTimeImmutable
                $dateDeces = \DateTimeImmutable::createFromFormat('d/m/Y', $data['dateDeces']);
                $auteur->setDateDeces($dateDeces);
            }
            $auteur->setNationalite($data['nationalite'] ?? null);
            $auteur->setPhoto($data['photo'] ?? null);
            $auteur->setDescription($data['description'] ?? null);

            $livreIds = $data['livres'] ?? [];
            foreach($livreIds as $livreId){
                $livre = $livreRepository->find($livreId);
                if($livre){
                    $auteur->addLivre($livre);
                }
            }
    
            $entityManager->persist($auteur);
            $entityManager->flush();
    
            return $this->json([
                'message' => 'Auteur créé avec succès.',
                'id' => $auteur->getId(),
                'nom' => $auteur->getNom(),
                'prenom' => $auteur->getPrenom(),
            ], 201);
        } else {
            return $this->json([
                'message' => 'Aucune donnée reçue ou données invalides.',
            ], 400);
        }
    }
    
    


    #[Route('/api/auteur/{id}', name: 'app_test', methods: ['TEST'])]
    public function test(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $nom = $request->query->get('nom');
        $prenom = $request->query->get('prenom');

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/AuteurController.php',
            'Nom' => $nom,
            'Prenom' => $prenom
        ]);
    }
    
}
