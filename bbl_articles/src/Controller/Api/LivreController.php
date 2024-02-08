<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LivreRepository;
use App\Entity\Livre;
use App\Repository\CategorieRepository;
use App\Entity\Categorie;
use App\Repository\AuteurRepository;
use App\Entity\Auteur;

class LivreController extends AbstractController
{
    #[Route('/api/livre', name: 'app_api_livre', methods: ['GET'])]
    public function index(Request $request, LivreRepository $livreRepository, CategorieRepository $categorieRepository, AuteurRepository $auteurRepository): JsonResponse
    {   
        $titre = $request->query->get('titre');
        $titre = preg_quote(strtolower($titre), '/');

        $dateSortie = $request->query->get('dateSortie');

        $langue = $request->query->get('langue');
        $langue = preg_quote(strtolower($langue), '/');

        $auteurName = $request->query->get('auteur');
        $auteurName = preg_quote(strtolower($auteurName), '/');

        $categorieName = $request->query->get('categorie');
        $categorieName = preg_quote(strtolower($categorieName), '/');

        $livres = $livreRepository->findAll();
        $data = [];

        foreach ($livres as $livre) {
            if(
                preg_match('/'.$titre.'/i', $livre->getTitre()) &&
                (
                    $dateSortie == null ||
                    $dateSortie == $livre->getDateSortie()->format('d/m/Y')
                ) &&
                preg_match('/'.$langue.'/i', $livre->getLangue())&&
                preg_match('/'.$categorieName.'/i', $livre->getCategorie()->getNom())
            ){
                $auteurIds = $livre->getAuteurs();
                $auteurs = [];
                foreach ($auteurIds as $auteurId) {
                    $auteur = $auteurRepository->find($auteurId);
                    if ($auteur) {
                        $auteurs[] = $auteur->getNom();
                    }
                }

                $auteursString = implode(', ', $auteurs);

                if(preg_match('/'.$auteurName.'/i', $auteursString)){
                    
                    
                    $categorieId = $livre->getCategorie();
                    $categorie = $categorieRepository->find($categorieId);
                    
                    $auteurIds = $livre->getAuteurs();
                    $auteurs = [];
                    foreach ($auteurIds as $auteurId) {
                        $auteur = $auteurRepository->find($auteurId);
                        if ($auteur) {
                            $auteurs[] = $auteur->getNom();
                        }
                    }
                    
                    $auteursString = implode(', ', $auteurs);
                    
                    $data[] = [
                        'id' => $livre->getId(),
                        'titre' => $livre->getTitre(),
                        'dateSortie' => $livre->getdateSortie(),
                        'langue' => $livre->getLangue(),
                        'photoCouverture' => $livre->getPhotoCouverture(),
                        'auteurs' => $auteursString,
                        'categorie'=> $categorie ? $categorie->getNom() : null,
                    ];
                }
            }
        }
        if (empty($data)) {
            return $this->json(['message' => 'No matching books found'], 404);
        }
        return $this->json($data);
    }

    #[Route('/api/livre/{id}', name: 'app_api_livre_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(LivreRepository $livreRepository, CategorieRepository $categorieRepository, AuteurRepository $auteurRepository, int $id): JsonResponse
    {
        $livre = $livreRepository->find($id);
        if ($livre === null) {
            return $this->json(['message' => 'Livre not found'], 404);
        }

        $categorieId = $livre->getCategorie();
        $categorie = $categorieRepository->find($categorieId);

        $auteurIds = $livre->getAuteurs();
        $auteurs = [];
        foreach ($auteurIds as $auteurId) {
            $auteur = $auteurRepository->find($auteurId);
            if ($auteur) {
                $auteurs[] = $auteur->getNom();
            }
        }

        $auteursString = implode(', ', $auteurs);

        $data = [
            'id' => $livre->getId(),
            'titre' => $livre->getTitre(),
            'dateSortie' => $livre->getdateSortie(),
            'langue' => $livre->getLangue(),
            'photoCouverture' => $livre->getPhotoCouverture(),
            'auteurs' => $auteursString,
            'categorie'=> $categorie ? $categorie->getNom() : null,
            // 'emprunts'=> $livre->getEmprunts(),
            // 'reservation'=> $livre->getReservations()
        ];
        return $this->json($data);
    }

    #[Route('/api/livre', name: 'app_api_livre_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, CategorieRepository $categorieRepository, AuteurRepository $auteurRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Récupérer les données du livre
        $titre = $data['titre'] ?? null;
        $dateSortie = $data['dateSortie'] ?? null;
        $langue = $data['langue'] ?? null;
        $photoCouverture = $data['photoCouverture'] ?? null;
        $categorieId = $data['categorieId'] ?? null;
        $auteurIds = $data['auteurIds'] ?? [];

        // Vérifier si les données requises sont présentes
        if (!$titre || !$dateSortie || !$langue || !$categorieId || empty($auteurIds)) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        // Créer un nouvel objet Livre
        $livre = new Livre();
        $livre->setTitre($titre);
        $livre->setDateSortie(new \DateTime($dateSortie));
        $livre->setLangue($langue);
        $livre->setPhotoCouverture($photoCouverture);

        // Récupérer et associer la catégorie
        $categorie = $categorieRepository->find($categorieId);
        if (!$categorie) {
            return new JsonResponse(['error' => 'Category not found'], 404);
        }
        $livre->setCategorie($categorie);

        // Récupérer et associer les auteurs
        foreach ($auteurIds as $auteurId) {
            $auteur = $auteurRepository->find($auteurId);
            if (!$auteur) {
                return new JsonResponse(['error' => 'Author not found'], 404);
            }
            $livre->addAuteur($auteur);
        }

        // Enregistrer le livre en base de données
        $entityManager->persist($livre);
        $entityManager->flush();

        // Retourner la réponse avec l'identifiant du livre créé
        return new JsonResponse(['message'=>'Livre created','id' => $livre->getId()], 201);
    }

    #[Route('/api/livre/{id}', name: 'app_api_livre_update', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(Request $request, EntityManagerInterface $entityManager, LivreRepository $livreRepository, CategorieRepository $categorieRepository, AuteurRepository $auteurRepository, int $id): JsonResponse
    {
        $livre = $livreRepository->find($id);
        if ($livre === null) {
            return new JsonResponse(['message' => 'Livre not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        // Mettre à jour les données du livre
        if (isset($data['titre'])) {
            $livre->setTitre($data['titre']);
        }
        if (isset($data['dateSortie'])) {
            $livre->setDateSortie(new \DateTime($data['dateSortie']));
        }
        if (isset($data['langue'])) {
            $livre->setLangue($data['langue']);
        }
        if (isset($data['photoCouverture'])) {
            $livre->setPhotoCouverture($data['photoCouverture']);
        }
        if (isset($data['categorieId'])) {
            $categorie = $categorieRepository->find($data['categorieId']);
            if (!$categorie) {
                return new JsonResponse(['error' => 'Category not found'], Response::HTTP_NOT_FOUND);
            }
            $livre->setCategorie($categorie);
        }
        if (isset($data['auteurIds'])) {
            $auteurIds = $data['auteurIds'];
            foreach ($auteurIds as $auteurId) {
                $auteur = $auteurRepository->find($auteurId);
                if (!$auteur) {
                    return new JsonResponse(['error' => 'Author not found'], Response::HTTP_NOT_FOUND);
                }
                $livre->addAuteur($auteur);
            }
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'Livre updated', 'id' => $livre->getId()], 200);
    }

    #[Route('/api/livre/{id}', name: 'app_api_livre_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(LivreRepository $livreRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $livre = $livreRepository->find($id);
        if ($livre === null) {
            return new JsonResponse(['message' => 'Livre not found'], 404);
        }

        $entityManager->remove($livre);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Livre deleted'], 200);
    }
}