<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre de l'événement est obligatoire.")]
    #[Assert\Length(min: 2, max: 5, minMessage: "Le titre de l'événement doit comporter au moins 2 caractères.", maxMessage: "Le titre de l'événement ne doit pas dépasser 5 caractères.")]
    private ?string $titreE = null;



    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "La date de l'événement est obligatoire.")]
    #[Assert\Type(type: "datetime", message: "La date doit être au format correct.")]
    #[Assert\GreaterThan("today", message: "La date de l'événement doit être une date future.")]
    private ?\DateTimeInterface $dateE = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[Assert\Length(min: 3, minMessage: "La description doit comporter au moins 3 caractères.")]
     private ?string $descriptionE = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le type d'événement est obligatoire.")]
    #[Assert\Length(min: 2, minMessage: "Le type d'événement doit comporter au moins 2 caractère.")]

    private ?string $typeE = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;


    #[ORM\ManyToOne(inversedBy: 'evenements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeEvenement $TypeEvenement = null;


    public function __construct()
    {
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreE(): ?string
    {
        return $this->titreE;
    }

    public function setTitreE(string $titreE): static
    {
        $this->titreE = $titreE;

        return $this;
    }

    public function getDateE(): ?\DateTimeInterface
    {
        return $this->dateE;
    }

    public function setDateE(\DateTimeInterface $dateE): static
    {
        $this->dateE = $dateE;

        return $this;
    }

    public function getDescriptionE(): ?string
    {
        return $this->descriptionE;
    }

    public function setDescriptionE(string $descriptionE): static
    {
        $this->descriptionE = $descriptionE;

        return $this;
    }

    public function getTypeE(): ?string
    {
        return $this->typeE;
    }

    public function setTypeE(string $typeE): static
    {
        $this->typeE = $typeE;

        return $this;
    }

    public function getTypeEvenement(): ?TypeEvenement
    {
        return $this->TypeEvenement;
    }

    public function setTypeEvenement(?TypeEvenement $TypeEvenement): static
    {
        $this->TypeEvenement = $TypeEvenement;

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
