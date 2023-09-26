<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profil = null;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Librairie::class, orphanRemoval: true)]
    private Collection $Librairie;

    public function __construct()
    {
        $this->Librairie = new ArrayCollection();
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
            $librairie->setMember($this);
        }

        return $this;
    }

    public function removeLibrairie(Librairie $librairie): static
    {
        if ($this->Librairie->removeElement($librairie)) {
            // set the owning side to null (unless already changed)
            if ($librairie->getMember() === $this) {
                $librairie->setMember(null);
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
}
