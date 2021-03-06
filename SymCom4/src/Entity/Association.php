<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AssociationRepository")
 */
class Association
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Structure", inversedBy="association", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid
     */
    private $structure;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Groupe", mappedBy="association", orphanRemoval=true)
     */
    private $groupes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sigle;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TypeAssociation", inversedBy="associations")
     */
    private $types;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
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
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->setAssociation($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->contains($groupe)) {
            $this->groupes->removeElement($groupe);
            // set the owning side to null (unless already changed)
            if ($groupe->getAssociation() === $this) {
                $groupe->setAssociation(null);
            }
        }

        return $this;
    }

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(?string $sigle): self
    {
        $this->sigle = $sigle;

        return $this;
    }

    /**
     * @return Collection|TypeAssociation[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(TypeAssociation $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
        }

        return $this;
    }

    public function removeType(TypeAssociation $type): self
    {
        if ($this->types->contains($type)) {
            $this->types->removeElement($type);
        }

        return $this;
    }
}
