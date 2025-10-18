<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Date de publication avec validation
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\GreaterThanOrEqual("today", message: "La date de publication ne peut pas être antérieure à aujourd'hui.")]
    private ?\DateTimeInterface $datePub = null;

    // Titre avec validation
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre de la formation ne peut pas être vide.")]
    #[Assert\Length(min: 3, minMessage: "Le titre doit comporter au moins {{ limit }} caractères.")]
    private ?string $titre = null;

    // Description avec validation
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description ne peut pas être vide.")]
    #[Assert\Length(min: 10, minMessage: "La description doit comporter au moins {{ limit }} caractères.")]
    private ?string $description = null;

    // Date limite avec validation par rapport à la date de publication
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message: "La date limite ne peut pas être vide.")]
    #[Assert\GreaterThanOrEqual(
        propertyPath: "datePub",
        message: "La date limite doit être après la date de publication."
    )]
    private ?\DateTimeInterface $datelimite = null;

    // Type de formation avec validation
    #[ORM\ManyToOne(inversedBy: 'formation')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeFormation $typeFormation = null;

    // Image
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

   
    
    // Getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePub(): ?\DateTimeInterface
    {
        return $this->datePub;
    }

    public function setDatePub(?\DateTimeInterface $datePub): static
    {
        $this->datePub = $datePub;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDatelimite(): ?\DateTimeInterface
    {
        return $this->datelimite;
    }

    public function setDatelimite(?\DateTimeInterface $datelimite): static
    {
        $this->datelimite = $datelimite;

        return $this;
    }

    public function getTypeFormation(): ?TypeFormation
    {
        return $this->typeFormation;
    }

    public function setTypeFormation(?TypeFormation $typeFormation): static
    {
        $this->typeFormation = $typeFormation;

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

   



   
}

