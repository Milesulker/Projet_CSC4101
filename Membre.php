<?php

namespace App\Entity;

use App\Repository\MembreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MembreRepository::class)]
class Membre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profil = null;

    #[ORM\OneToMany(mappedBy: 'membre', targetEntity: Librairie::class, orphanRemoval: true)]
    private Collection $Librairie;

    #[ORM\OneToMany(mappedBy: 'Createur', targetEntity: Galerie::class, orphanRemoval: true)]
    private Collection $galeries;

    public function __construct()
    {
        $this->Librairie = new ArrayCollection();
        $this->galeries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getProfil(): ?string
    {
        return $this->profil;
    }

    public function setProfil(?string $profil): static
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * @return Collection<int, Librairie>
     */
    public function getLibrairie(): Collection
    {
        return $this->Librairie;
    }

    public function addLibrairie(Librairie $librairie): static
    {
        if (!$this->Librairie->contains($librairie)) {
            $this->Librairie->add($librairie);
            $librairie->setMembre($this);
        }

        return $this;
    }

    public function removeLibrairie(Librairie $librairie): static
    {
        if ($this->Librairie->removeElement($librairie)) {
            // set the owning side to null (unless already changed)
            if ($librairie->getMembre() === $this) {
                $librairie->setMembre(null);
            }
        }

        return $this;
    }
    
    public function __toString()
    {
        $s = '';
        $s .= $this->getNom();
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
            $galery->setCreateur($this);
        }

        return $this;
    }

    public function removeGalerie(Galerie $galery): static
    {
        if ($this->galeries->removeElement($galery)) {
            // set the owning side to null (unless already changed)
            if ($galery->getCreateur() === $this) {
                $galery->setCreateur(null);
            }
        }

        return $this;
    }
}
