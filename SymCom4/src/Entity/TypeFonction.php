<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeFonctionRepository")
 */
class TypeFonction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titreFeminin;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Fonction", mappedBy="typeFonction")
     */
    private $fonction;

    public function __construct()
    {
        $this->fonction = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getTitreFeminin(): ?string
    {
        return $this->titreFeminin;
    }

    public function setTitreFeminin(string $titreFeminin): self
    {
        $this->titreFeminin = $titreFeminin;

        return $this;
    }

    /**
     * @return Collection|Fonction[]
     */
    public function getFonction(): Collection
    {
        return $this->fonction;
    }

    public function addFonction(Fonction $fonction): self
    {
        if (!$this->fonction->contains($fonction)) {
            $this->fonction[] = $fonction;
            $fonction->setTypeFonction($this);
        }

        return $this;
    }

    public function removeFonction(Fonction $fonction): self
    {
        if ($this->fonction->contains($fonction)) {
            $this->fonction->removeElement($fonction);
            // set the owning side to null (unless already changed)
            if ($fonction->getTypeFonction() === $this) {
                $fonction->setTypeFonction(null);
            }
        }

        return $this;
    }
}
