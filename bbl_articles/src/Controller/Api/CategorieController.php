<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategorieRepository;
use App\Entity\Categorie;

class CategorieController extends AbstractController
{
    #[Route('/api/categorie', name: 'app_api_categorie', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): JsonResponse
    {
        $categories = $categorieRepository->findAll();
        $data = [];
        foreach ($categories as $categorie) {
            $data[] = [
                'id' => $categorie->getId(),
                'nom' => $categorie->getNom(),
                'description' => $categorie->getDescription(),
            ];
        }
        return $this->json($data);
    }

    #[Route('/api/categorie/{id}', name: 'app_api_categorie_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(CategorieRepository $categorieRepository, int $id): JsonResponse
    {
        $categorie = $categorieRepository->find($id);
        if ($categorie === null) {
            return $this->json(['message' => 'Categorie not found'], 404);
        }
        $data = [
            'id' => $categorie->getId(),
            'nom' => $categorie->getNom(),
            'description' => $categorie->getDescription(),
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