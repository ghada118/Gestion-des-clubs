<?php

namespace App\Entity;

use App\Repository\TypeClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TypeClubRepository::class)]
class TypeClub
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de la catégorie est obligatoire.")]
    #[Assert\Length(
        min: 2,
        max: 15,
        minMessage: "Le nom de la catégorie doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom de la catégorie ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $categorieClub = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[Assert\Length(
        min: 5,
        max: 50,
        minMessage: "La description doit contenir au moins {{ limit }} caractères.",
        maxMessage: "La description ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $descriptionCategClub = null;

    /**
     * @var Collection<int, Club>
     */
    #[ORM\OneToMany(targetEntity: Club::class, mappedBy: 'type', cascade: ['remove'])]
    private Collection $clubs;

    public function __construct()
    {
        $this->clubs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorieClub(): ?string
    {
        return $this->categorieClub;
    }

    public function setCategorieClub(string $categorieClub): static
    {
        $this->categorieClub = $categorieClub;

        return $this;
    }

    public function getDescriptionCategClub(): ?string
    {
        return $this->descriptionCategClub;
    }

    public function setDescriptionCategClub(string $descriptionCategClub): static
    {
        $this->descriptionCategClub = $descriptionCategClub;

        return $this;
    }

    /**
     * @return Collection<int, Club>
     */
    public function getClubs(): Collection
    {
        return $this->clubs;
    }

    public function addClub(Club $club): static
    {
        if (!$this->clubs->contains($club)) {
            $this->clubs->add($club);
            $club->setType($this);
        }

        return $this;
    }

    public function removeClub(Club $club): static
    {
        if ($this->clubs->removeElement($club)) {
            // set the owning side to null (unless already changed)
            if ($club->getType() === $this) {
                $club->setType(null);
            }
        }

        return $this;
    }
}
