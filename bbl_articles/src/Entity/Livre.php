<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateSortie = null;

    #[ORM\Column(length: 255)]
    private ?string $langue = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoCouverture = null;

    #[ORM\ManyToMany(targetEntity: Auteur::class, inversedBy: 'livres', fetch: 'LAZY')]
    private Collection $auteurs;

    #[ORM\ManyToOne(inversedBy: 'livres',fetch: 'LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'livre', targetEntity: Emprunt::class, orphanRemoval: true,fetch: 'LAZY')]
    private Collection $emprunts;

    #[ORM\OneToOne(mappedBy: 'livre', cascade: ['persist', 'remove'],fetch: 'LAZY')]
    private ?Reservations $reservations = null;


    public function __construct()
    {
        $this->auteurs = new ArrayCollection();
        $this->emprunts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->dateSortie;
    }

    public function setDateSortie(\DateTimeInterface $dateSortie): static
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): static
    {
        $this->langue = $langue;

        return $this;
    }

    public function getPhotoCouverture(): ?string
    {
        return $this->photoCouverture;
    }

    public function setPhotoCouverture(?string $photoCouverture): static
    {
        $this->photoCouverture = $photoCouverture;

        return $this;
    }

    /**
     * @return Collection<int, Auteur>
     */
    public function getAuteurs(): Collection
    {
        return $this->auteurs;
    }

    public function addAuteur(Auteur $auteur): static
    {
    if (!$this->auteurs->contains($auteur)) {
        $this->auteurs->add($auteur);
        $auteur->addLivre($this);
    }

    return $this;
    }

    public function removeAuteur(Auteur $auteur): static
    {
        $this->auteurs->removeElement($auteur);

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Emprunt>
     */
    public function getEmprunts(): Collection
    {
        return $this->emprunts;
    }

    public function addEmprunt(Emprunt $emprunt): static
    {
        if (!$this->emprunts->contains($emprunt)) {
            $this->emprunts->add($emprunt);
            $emprunt->setLivre($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): static
    {
        if ($this->emprunts->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getLivre() === $this) {
                $emprunt->setLivre(null);
            }
        }

        return $this;
    }

    public function getReservations(): ?Reservations
    {
        return $this->reservations;
    }

    public function setReservations(Reservations $reservations): static
    {
        // set the owning side of the relation if necessary
        if ($reservations->getLivre() !== $this) {
            $reservations->setLivre($this);
        }

        $this->reservations = $reservations;

        return $this;
    }
    public function __toString()
    {
        return $this->titre;
    }


}
