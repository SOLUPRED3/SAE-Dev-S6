<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationsRepository;
use App\Entity\Reservations;
use App\Repository\AdherentRepository;
use App\Entity\Adherent;
use App\Repository\LivreRepository;
use App\Entity\Livre;

class ReservationsController extends AbstractController
{
    #[Route('/api/reservations', name: 'app_api_reservations', methods: ['GET'])]
    public function index(Request $request, ReservationsRepository $reservationsRepository ,LivreRepository $livreRepository, AdherentRepository $adherentRepository): JsonResponse
    {
        $dateResa = $request->query->get('dateResa');

        $reservateurName = $request->query->get('reservateur');

        $livreName = $request->query->get('livre');

        $reservations = $reservationsRepository->findAll();
        $data = [];

        foreach($reservations as $reservation){
            if(
                (
                    $dateResa == null ||
                    $dateResa == $reservation->getDateResa()->format('d/m/Y')
                ) &&
                preg_match('/'.$livreName.'/i', $reservation->getLivre()->getTitre()) &&
                preg_match('/'.$reservateurName.'/i', $reservation->getReservateur()->getNom())
            ){
                
                $reservateurId = $reservation->getReservateur();
                $reservateur = $adherentRepository->find($reservateurId);
                
                $livreId = $reservation->getLivre();
                $livre = $livreRepository->find($livreId);
                
                $data[] = [
                    'id' => $reservation->getId(),
                    'dateResa' => $reservation->getDateResa(),
                    'reservateur' => $reservateur ? $reservateur->getNom() : null,
                    'livre' => $livre ? $livre->getTitre() : null,
                ];
            }
        }
        if(empty($data)){
            return new JsonResponse(['message' => 'No reservation found'], 404);
        }
        return $this->json($data);
    }

    #[Route('/api/reservations/{id}', name: 'app_api_reservations_get', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function getById(int $id, ReservationsRepository $reservationsRepository, LivreRepository $livreRepository, AdherentRepository $adherentRepository): JsonResponse
    {
        $reservation = $reservationsRepository->find($id);
        if ($reservation === null) {
            return new JsonResponse(['message' => 'Reservation not found'], 404);
        }

        $dateResa = $reservation->getDateResa();

        $reservateurId = $reservation->getReservateur();
        $reservateur = $adherentRepository->find($reservateurId);

        $livreId = $reservation->getLivre();
        $livre = $livreRepository->find($livreId);

        $data = [
            'id' => $id,
            'dateResa' => $dateResa,
            'reservateur' => $reservateur ? $reservateur->getNom() : null,
            'livre' => $livre ? $livre->getTitre() : null,
        ];
        return $this->json($data);
    }

    #[Route('/api/reservations', name: 'app_api_reservations_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, LivreRepository $livreRepository, AdherentRepository $adherentRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $dateResa = $data['dateResa'] ?? null;
        $reservateurId = $data['reservateurId'] ?? null;
        $livreId = $data['livreId'] ?? null;
        
        if($dateResa === null || $reservateurId === null || $livreId === null){
            return $this->json(['message' => 'Missing required data'], 400);
        }
        
        $dateResa = \DateTime::createFromFormat('d/m/Y', $dateResa);
        
        if($dateResa === false){
            return $this->json(['message' => 'Invalid date format. Please use dd/mm/yyyy format.'], 400);
        }
        
        
        $reservateur = $adherentRepository->find($reservateurId);
        if($reservateur === null){
            return $this->json(['message' => 'Adherent not found'], 404);
        }
        
        $livre = $livreRepository->find($livreId);
        if($livre === null){
            return $this->json(['message' => 'Livre not found'], 404);
        }
        
        $reservation = new Reservations();
        $reservation->setDateResa($dateResa);
        $reservation->setReservateur($reservateur);
        $reservation->setLivre($livre);

        $entityManager->persist($reservation);
        $entityManager->flush();
        return $this->json(['message' => 'Reservation created', 'id' => $reservation->getId()], 201);
    }

    #[Route('/api/reservations/{id}', name: 'app_api_reservations_update', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(Request $request, EntityManagerInterface $entityManager, ReservationsRepository $reservationsRepository, LivreRepository $livreRepository, AdherentRepository $adherentRepository, int $id): JsonResponse
    {
        $reservation = $reservationsRepository->find($id);
        if ($reservation === null) {
            return $this->json(['message' => 'Reservation not found'], 404);
        }
        $data = json_decode($request->getContent(), true);
        
        $dateResa = $data['dateResa'] ?? null;
        $reservateurId = $data['reservateurId'] ?? null;
        $livreId = $data['livreId'] ?? null;

        if($dateResa === null || $reservateurId === null || $livreId === null){
            return $this->json(['message' => 'Missing required data'], 400);
        }
        
        $dateResa = \DateTime::createFromFormat('d/m/Y', $dateResa);
        
        if($dateResa === false){
            return $this->json(['message' => 'Invalid date format. Please use dd/mm/yyyy format.'], 400);
        }
        
        
        $reservateur = $adherentRepository->find($reservateurId);
        if($reservateur === null){
            return $this->json(['message' => 'Adherent not found'], 404);
        }
        
        $livre = $livreRepository->find($livreId);
        if($livre === null){
            return $this->json(['message' => 'Livre not found'], 404);
        }

        $reservation->setDateResa($dateResa);
        $reservation->setReservateur($reservateur);
        $reservation->setLivre($livre);

        $entityManager->flush();

        return $this->json(['message' => 'Reservation updated'], 200);
    }

    #[Route('/api/reservations/{id}', name: 'app_api_reservations_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(ReservationsRepository $reservationsRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $reservation = $reservationsRepository->find($id);
        if ($reservation === null) {
            return $this->json(['message' => 'Reservation not found'], 404);
        }

        $entityManager->remove($reservation);
        $entityManager->flush();

        return $this->json(['message' => 'Reservation deleted'], 200);
    }
}