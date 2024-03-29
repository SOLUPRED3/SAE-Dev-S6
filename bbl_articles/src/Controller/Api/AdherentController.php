<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AdherentRepository;
use App\Entity\Adherent;

class AdherentController extends AbstractController
{
    #[Route('/api/adherent', name: 'app_api_adherent', methods: ['GET'])]
    public function index(AdherentRepository $adherentRepository): JsonResponse
    {
        $adherents = $adherentRepository->findAll();
        $data = [];
        foreach ($adherents as $adherent) {
            $data[] = [
                'id' => $adherent->getId(),
                'dateAdhesion' => $adherent->getDateAdhesion(),
                'nom' => $adherent->getNom(),
                'prenom' => $adherent->getPrenom(),
                'dateNaiss' => $adherent->getDateNaiss(),
                'email' => $adherent->getEmail(),
                'adressePostale' => $adherent->getAdressePostale(),
                'numTel' => $adherent->getNumTel(),
                'photo' => $adherent->getPhoto(),
                'roles' => $adherent->getRoles(),
                'password' => $adherent->getPassword(),
            ];
        }
        return $this->json($data);
    }

    #[Route('/api/adherent/{id}', name: 'app_api_adherent_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(AdherentRepository $adherentRepository, int $id): JsonResponse
    {
        $adherent = $adherentRepository->find($id);
        if ($adherent === null) {
            return $this->json(['message' => 'Adherent not found'], 404);
        }
        $data = [
            'id' => $adherent->getId(),
            'dateAdhesion' => $adherent->getDateAdhesion(),
            'nom' => $adherent->getNom(),
            'prenom' => $adherent->getPrenom(),
            'dateNaiss' => $adherent->getDateNaiss(),
            'email' => $adherent->getEmail(),
            'adressePostale' => $adherent->getAdressePostale(),
            'numTel' => $adherent->getNumTel(),
            'photo' => $adherent->getPhoto(),
            'roles' => $adherent->getRoles(),
            'password' => $adherent->getPassword(),
        ];
        return $this->json($data);
    }

    #[Route('/api/adherent/{id}', name: 'app_api_adherent_update', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(Request $request, EntityManagerInterface $em, AdherentRepository $adherentRepository, int $id): JsonResponse
    {
        $adherent = $adherentRepository->find($id);
        if ($adherent === null) {
            return $this->json(['message' => 'Adherent not found'], 404);
        }
        $data = json_decode($request->getContent(), true);
        
        $dateAdhesion = $data['dateAdhesion'] ?? null;
        $nom = $data['nom'] ?? null;
        $prenom = $data['prenom'] ?? null;
        $dateNaiss = $data['dateNaiss'] ?? null;
        $email = $data['email'] ?? null;
        $adressePostale = $data['adressePostale'] ?? null;
        $numTel = $data['numTel'] ?? null;
        $photo = $data['photo'] ?? null;
        $roles = $data['roles'] ?? null;
        $password = $data['password'] ?? null;

        if ($dateAdhesion !== null) {
            $dateAdhesion = \DateTime::createFromFormat('d/m/Y', $dateAdhesion);
            $adherent->setDateAdhesion(new \DateTime($dateAdhesion));
        }
        if ($nom !== null) {
            $adherent->setNom($nom);
        }
        if ($prenom !== null) {
            $adherent->setPrenom($prenom);
        }
        if ($dateNaiss !== null) {
            $dateNaiss = \DateTime::createFromFormat('d/m/y', $dateNaiss);
            $adherent->setDateNaiss(new \DateTime($dateNaiss));
        }
        if ($email !== null) {
            $adherent->setEmail($email);
        }
        if ($adressePostale !== null) {
            $adherent->setAdressePostale($adressePostale);
        }
        if ($numTel !== null) {
            $adherent->setNumTel($numTel);
        }
        if ($photo !== null) {
            $adherent->setPhoto($photo);
        }
        if ($roles !== null) {
            $adherent->setRoles($roles);
        }
        if ($password !== null) {
            $adherent->setPassword($password);
        }
        $em->flush();

        return $this->json(['message' => 'Adherent updated']);
    }

    #[Route('/api/adherent', name: 'app_api_adherent_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $dateAdhesion = $data['dateAdhesion'] ?? null;
        $nom = $data['nom'] ?? null;
        $prenom = $data['prenom'] ?? null;
        $dateNaiss = $data['dateNaiss'] ?? null;
        $email = $data['email'] ?? null;
        $adressePostale = $data['adressePostale'] ?? null;
        $numTel = $data['numTel'] ?? null;
        $photo = $data['photo'] ?? null;
        $roles = $data['roles'] ?? null;
        $password = $data['password'] ?? null;

        $adherent = new Adherent();
        if ($dateAdhesion !== null) {
            $dateAdhesion = \DateTime::createFromFormat('d/m/Y', $dateAdhesion);
            $adherent->setDateAdhesion(new \DateTime($dateAdhesion));
        }
        if ($nom !== null) {
            $adherent->setNom($nom);
        }
        if ($prenom !== null) {
            $adherent->setPrenom($prenom);
        }
        if ($dateNaiss !== null) {
            $dateNaiss = \DateTime::createFromFormat('d/m/y', $dateNaiss);
            $adherent->setDateNaiss(new \DateTime($dateNaiss));
        }
        if ($email !== null) {
            $adherent->setEmail($email);
        }
        if ($adressePostale !== null) {
            $adherent->setAdressePostale($adressePostale);
        }
        if ($numTel !== null) {
            $adherent->setNumTel($numTel);
        }
        if ($photo !== null) {
            $adherent->setPhoto($photo);
        }
        if ($roles !== null) {
            $adherent->setRoles($roles);
        }
        if ($password !== null) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $adherent->setPassword($password);
        }
        $em->persist($adherent);
        $em->flush();

        return $this->json(['message' => 'Adherent created', 'id' => $adherent->getId()], 201);
    }

    #[Route('/api/adherent/{id}', name: 'app_api_adherent_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(AdherentRepository $adherentRepository, EntityManagerInterface $em, int $id): JsonResponse
    {
        $adherent = $adherentRepository->find($id);
        if ($adherent === null) {
            return $this->json(['message' => 'Adherent not found'], 404);
        }
        $em->remove($adherent);
        $em->flush();

        return $this->json(['message' => 'Adherent deleted']);
    }
}