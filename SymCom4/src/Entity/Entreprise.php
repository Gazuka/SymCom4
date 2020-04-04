<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntrepriseRepository")
 */
class Entreprise
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Structure", inversedBy="entreprise", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $structure;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TypeEntreprise", inversedBy="entreprises")
     */
    private $types;

    public function __construct()
    {
        $this->types = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    public function setStructure(Structure $structure): self
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * @return Collection|TypeEntreprise[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(TypeEntreprise $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
        }

        return $this;
    }

    public function removeType(TypeEntreprise $type): self
    {
        if ($this->types->contains($type)) {
            $this->types->removeElement($type);
        }

        return $this;
    }
}
