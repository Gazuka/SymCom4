<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $structure;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeAssociation", inversedBy="associations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Groupe", mappedBy="association", orphanRemoval=true)
     */
    private $groupes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sigle;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
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

    public function getType(): ?TypeAssociation
    {
        return $this->type;
    }

    public function setType(?TypeAssociation $type): self
    {
        $this->type = $type;

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
}
