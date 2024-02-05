<?php

namespace App\Entity;

use App\Repository\ReservationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationsRepository::class)]
class Reservations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateResa = null;

    #[ORM\OneToOne(inversedBy: 'reservations', cascade: ['persist', 'remove'],fetch: 'LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Livre $livre = null;

    #[ORM\OneToOne(inversedBy: 'reservations', cascade: ['persist', 'remove'],fetch: 'LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adherent $reservateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateResa(): ?\DateTimeInterface
    {
        return $this->dateResa;
    }

    public function setDateResa(\DateTimeInterface $dateResa): static
    {
        $this->dateResa = $dateResa;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(Livre $livre): static
    {
        $this->livre = $livre;

        return $this;
    }

    public function getReservateur(): ?Adherent
    {
        return $this->reservateur;
    }

    public function setReservateur(Adherent $reservateur): static
    {
        $this->reservateur = $reservateur;

        return $this;
    }
}
