<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeAssociationRepository")
 */
class TypeAssociation
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
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Groupe", mappedBy="type")
     */
    private $groupes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeAssociation", inversedBy="enfants")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TypeAssociation", mappedBy="parent")
     */
    private $enfants;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Association", mappedBy="types")
     */
    private $associations;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->enfants = new ArrayCollection();
        $this->associations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getParentName().$this->nom;
    }

    public function getParentName()
    {
        if($this->parent != null)
        {
            $ParentName = $this->parent->getParentName().$this->parent->getNom()." > ";
        }
        else
        {
            $ParentName = '';
        }
        return $ParentName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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
            $groupe->setType($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->contains($groupe)) {
            $this->groupes->removeElement($groupe);
            // set the owning side to null (unless already changed)
            if ($groupe->getType() === $this) {
                $groupe->setType(null);
            }
        }

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getEnfants(): Collection
    {
        return $this->enfants;
    }

    public function addEnfant(self $enfant): self
    {
        if (!$this->enfants->contains($enfant)) {
            $this->enfants[] = $enfant;
            $enfant->setParent($this);
        }

        return $this;
    }

    public function removeEnfant(self $enfant): self
    {
        if ($this->enfants->contains($enfant)) {
            $this->enfants->removeElement($enfant);
            // set the owning side to null (unless already changed)
            if ($enfant->getParent() === $this) {
                $enfant->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Association[]
     */
    public function getAssociations(): Collection
    {
        return $this->associations;
    }

    public function addAssociation(Association $association): self
    {
        if (!$this->associations->contains($association)) {
            $this->associations[] = $association;
            $association->addType($this);
        }

        return $this;
    }

    public function removeAssociation(Association $association): self
    {
        if ($this->associations->contains($association)) {
            $this->associations->removeElement($association);
            $association->removeType($this);
        }

        return $this;
    }
}
