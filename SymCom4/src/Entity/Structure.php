<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Illustration", inversedBy="structures")
     */
    private $illustration;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->fonctions = new ArrayCollection();
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

    public function getSite(): ?Lien
    {
        return $this->site;
    }

    public function setSite(?Lien $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getIllustration(): ?Illustration
    {
        return $this->illustration;
    }

    public function setIllustration(?Illustration $illustration): self
    {
        $this->illustration = $illustration;

        return $this;
    }
}
