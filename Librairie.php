<?php

namespace App\Entity;

use App\Repository\LibrairieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LibrairieRepository::class)]
class Librairie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\OneToMany(mappedBy: 'librairie', targetEntity: Livre::class, orphanRemoval: true, cascade: ["persist"])]
    private Collection $livre;

    #[ORM\ManyToOne(inversedBy: 'Librairie')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Membre $membre = null;

    public function __construct()
    {
        $this->livre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    /**
     * @return Collection<int, Livre>
     */
    public function getLivre(): Collection
    {
        return $this->livre;
    }

    public function showLivres(): string
    {
        $livres= $this->getLivre();
        $s='';
        foreach ($livres as $livre)
        {
            $titre=$livre->getTitre();
            $s.= "- " . $titre . " ";
        }
        return $s;
    }
    
    public function addLivre(Livre $livre): static
    {
        if (!$this->livre->contains($livre)) {
            $this->livre->add($livre);
            $livre->setLibrairie($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): static
    {
        if ($this->livre->removeElement($livre)) {
            // set the owning side to null (unless already changed)
            if ($livre->getLibrairie() === $this) {
                $livre->setLibrairie(null);
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

    public function getMembre(): ?Membre
    {
        return $this->membre;
    }

    public function setMembre(?Membre $membre): static
    {
        $this->membre = $membre;

        return $this;
    }
}
