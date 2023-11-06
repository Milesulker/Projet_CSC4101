<?php

namespace App\Entity;

use App\Repository\GalerieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GalerieRepository::class)]
class Galerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $public = null;

    #[ORM\ManyToOne(inversedBy: 'galeries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Membre $Createur = null;

    #[ORM\ManyToMany(targetEntity: Livre::class, inversedBy: 'galeries')]
    private Collection $Objets;

    public function __construct()
    {
        $this->Objets = new ArrayCollection();
    }
    
    public function __toString()
    {
        $s = '';
        $s .= $this->getDescription();
        return $s;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function isPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): static
    {
        $this->public = $public;

        return $this;
    }

    public function getCreateur(): ?Membre
    {
        return $this->Createur;
    }

    public function setCreateur(?Membre $Createur): static
    {
        $this->Createur = $Createur;

        return $this;
    }

    /**
     * @return Collection<int, Livre>
     */
    public function getObjets(): Collection
    {
        return $this->Objets;
    }

    public function addObjet(Livre $objet): static
    {
        if (!$this->Objets->contains($objet)) {
            $this->Objets->add($objet);
        }

        return $this;
    }

    public function removeObjet(Livre $objet): static
    {
        $this->Objets->removeElement($objet);

        return $this;
    }
}
