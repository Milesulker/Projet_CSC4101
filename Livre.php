<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Titre = null;

    #[ORM\Column]
    private ?int $Niveau = null;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[ORM\ManyToOne(inversedBy: 'livre')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Librairie $librairie = null;

    #[ORM\ManyToMany(targetEntity: Galerie::class, mappedBy: 'Objets')]
    private Collection $galeries;

    public function __construct()
    {
        $this->galeries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): static
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getNiveau(): ?int
    {
        return $this->Niveau;
    }

    public function setNiveau(int $Niveau): static
    {
        $this->Niveau = $Niveau;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getLibrairie(): ?Librairie
    {
        return $this->librairie;
    }

    public function setLibrairie(?Librairie $librairie): static
    {
        $this->librairie = $librairie;

        return $this;
    }
    
    public function __toString()
    {
        $s = '';
        $s .= $this->getTitre();
        return $s;
    }

    /**
     * @return Collection<int, Galerie>
     */
    public function getGaleries(): Collection
    {
        return $this->galeries;
    }

    public function addGalerie(Galerie $galery): static
    {
        if (!$this->galeries->contains($galery)) {
            $this->galeries->add($galery);
            $galery->addObjet($this);
        }

        return $this;
    }

    public function removeGalerie(Galerie $galery): static
    {
        if ($this->galeries->removeElement($galery)) {
            $galery->removeObjet($this);
        }

        return $this;
    }
}
