<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategorieRepository;
use App\Entity\Categorie;
use App\Repository\LivreRepository;
use App\Entity\Livre;

class CategorieController extends AbstractController
{
    #[Route('/api/categorie', name: 'app_api_categorie', methods: ['GET'])]
    public function index(Request $request, CategorieRepository $categorieRepository, LivreRepository $livreRepository): JsonResponse
    {
        $nom = $request->query->get('nom');
        $nom = preg_quote(strtolower($nom), '/');

        $description = $request->query->get('description');
        $description = preg_quote(strtolower($description), '/');


        $categories = $categorieRepository->findAll();
        $livres = $livreRepository->findAll();

        $responseSize = $request->query->get('size');
        $responseOffset = $request->query->get('offset');
        
        $data = [];
        
        foreach ($categories as $categorie) {
            if(
                preg_match('/'.$nom.'/i', $categorie->getNom()) &&
                preg_match('/'.$description.'/i', $categorie->getDescription())
            ){
                $livresData = [];
                foreach($livres as $livre){
                    if($livre->getCategorie()->getId() == $categorie->getId()){
                        $livresData[] = $livre->getTitre();
                    }
                }
                $data[] = [
                    'id' => $categorie->getId(),
                    'nom' => $categorie->getNom(),
                    'description' => $categorie->getDescription(),
                    'livres' => $livresData,
                ];
            }
        }
        if(empty($data)){
            return $this->json(['message' => 'No categorie found'], 404);
        }

        if($responseSize !== null && $responseOffset !== null){
            $data = array_slice($data, $responseOffset, $responseSize);
        }
        
        return $this->json($data);
    }

    #[Route('/api/categorie/{id}', name: 'app_api_categorie_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(CategorieRepository $categorieRepository, LivreRepository $livreRepository,int $id): JsonResponse
    {
        $categorie = $categorieRepository->find($id);
        if ($categorie === null) {
            return $this->json(['message' => 'Categorie not found'], 404);
        }
        $livres = $livreRepository->findAll();
        $livresData = [];
                foreach($livres as $livre){
                    if($livre->getCategorie()->getId() == $categorie->getId()){
                        $livresData[] = $livre->getTitre();
                    }
                }
        $data = [
            'id' => $categorie->getId(),
            'nom' => $categorie->getNom(),
            'description' => $categorie->getDescription(),
            'livres' => $livresData,
        ];
        return $this->json($data);
    }

    #[Route('/api/categorie', name: 'app_api_categorie_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $categorie = new Categorie();

        $nom = $data['nom'] ?? null;
        $description = $data['description'] ?? null;

        if($nom !== null){
            $categorie->setNom($nom);
        }
        if($description !== null){
            $categorie->setDescription($description);
        }

        $entityManager->persist($categorie);
        $entityManager->flush();
        return $this->json(['id' => $categorie->getId()], 201);
    }

    #[Route('/api/categorie/{id}', name: 'app_api_categorie_update', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(Request $request, EntityManagerInterface $entityManager, CategorieRepository $categorieRepository, int $id): JsonResponse
    {
        $categorie = $categorieRepository->find($id);
        if ($categorie === null) {
            return $this->json(['message' => 'Categorie not found'], 404);
        }
        $data = json_decode($request->getContent(), true);

        $nom = $data['nom'] ?? null;
        $description = $data['description'] ?? null;

        if($nom !== null){
            $categorie->setNom($nom);
        }
        if($description !== null){
            $categorie->setDescription($description);
        }

        $entityManager->flush();
        return $this->json(['message'=> 'Categorie updated', 'id'=>$categorie->getId()], 201);
    }

    #[Route('/api/categorie/{id}', name: 'app_api_categorie_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(CategorieRepository $categorieRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $categorie = $categorieRepository->find($id);
        if ($categorie === null) {
            return $this->json(['message' => 'Categorie not found'], 404);
        }

        $nom = $data['nom'] ?? null;
        $description = $data['description'] ?? null;

        if($nom !== null){
            $categorie->setNom($nom);
        }
        if($description !== null){
            $categorie->setDescription($description);
        }


        $entityManager->remove($categorie);
        $entityManager->flush();
        return $this->json(['message' => 'Categorie deleted'], 200);
    }
}