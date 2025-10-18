<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClubRepository::class)]
class Club
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du club est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[Assert\Length(
         min: 10,
         max: 255,
         minMessage: "La description doit contenir au moins {{ limit }} caractères.",
         maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "Le champ est vide, il faut entrer une date de création.")]
    #[Assert\GreaterThanOrEqual(
        value: "2024-01-01",
        message: "La date de création doit être en 2024 ou plus tard."
    )]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(inversedBy: 'clubs')]
    #[Assert\NotBlank(message: "Le champ est vide, il faut entrer un type de club.")]
    private ?TypeClub $type = null;

    // Collection des étudiants inscrits dans ce club
    #[ORM\ManyToMany(targetEntity: Etudiant::class, inversedBy: 'clubs')]
    private Collection $etudiants;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private ?int $placesDisponibles = 0; // Exemple avec 10 places par défaut.

    private $entityManager;

    public function __construct()
    {
        $this->dateCreation = new \DateTime(); // Initialisation de la date de création à la date actuelle
        $this->etudiants = new ArrayCollection(); // Initialisation de la collection d'étudiants
    }

    // Getters et setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getType(): ?TypeClub
    {
        return $this->type;
    }

    public function setType(?TypeClub $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants[] = $etudiant;
        }
        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): self
    {
        $this->etudiants->removeElement($etudiant);

        return $this;
    }

    public function getPlacesDisponibles(): ?int
    {
        return $this->placesDisponibles;
    }

    public function setPlacesDisponibles(int $placesDisponibles): self
    {
        $this->placesDisponibles = $placesDisponibles;
        
        return $this;
    }
}
