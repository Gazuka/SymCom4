<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StructureRepository")
 */
class Structure
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 5, max = 100, minMessage = "Le nom doit comporter au minimum 5 caractères.", maxMessage = "Le nom doit comporter au maximum 100 caractères.")
     */
    private $nom;

    /**
     * @ORM\Column(type="text", nullable=true)
    */
    private $presentation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $local;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Contact", inversedBy="structures")
     */
    private $contacts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Fonction", mappedBy="structure", orphanRemoval=true)
     */
    private $fonctions;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Association", mappedBy="structure", cascade={"persist", "remove"})
     */
    private $association;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Service", mappedBy="structure", cascade={"persist", "remove"})
     */
    private $service;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Entreprise", mappedBy="structure", cascade={"persist", "remove"})
     */
    private $entreprise;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Lien", inversedBy="structure", cascade={"persist", "remove"})
     */
    private $lien;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Image", inversedBy="structures")
     */
    private $image;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->fonctions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom;
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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getLocal(): ?bool
    {
        return $this->local;
    }

    public function setLocal(bool $local): self
    {
        $this->local = $local;

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
        }

        return $this;
    }

    /**
     * @return Collection|Fonction[]
     */
    public function getFonctions(): Collection
    {
        return $this->fonctions;
    }

    public function addFonction(Fonction $fonction): self
    {
        if (!$this->fonctions->contains($fonction)) {
            $this->fonctions[] = $fonction;
            $fonction->setStructure($this);
        }

        return $this;
    }

    public function removeFonction(Fonction $fonction): self
    {
        if ($this->fonctions->contains($fonction)) {
            $this->fonctions->removeElement($fonction);
            // set the owning side to null (unless already changed)
            if ($fonction->getStructure() === $this) {
                $fonction->setStructure(null);
            }
        }

        return $this;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(Association $association): self
    {
        $this->association = $association;

        // set the owning side of the relation if necessary
        if ($association->getStructure() !== $this) {
            $association->setStructure($this);
        }

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(Service $service): self
    {
        $this->service = $service;

        // set the owning side of the relation if necessary
        if ($service->getStructure() !== $this) {
            $service->setStructure($this);
        }

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        // set the owning side of the relation if necessary
        if ($entreprise->getStructure() !== $this) {
            $entreprise->setStructure($this);
        }

        return $this;
    }

    public function getLien(): ?Lien
    {
        return $this->lien;
    }

    public function setLien(?Lien $lien): self
    {
        $this->lien = $lien;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getType()
    {
        $type = null;
        if($this->association != null)
        {
            $type = 'association';
        }
        if($this->service != null)
        {
            $type = 'service';
        }
        if($this->entreprise != null)
        {
            $type = 'entreprise';
        }
        return $type;
    }
}
