<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FonctionRepository")
 */
class Fonction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Structure", inversedBy="fonctions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $structure;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Contact", inversedBy="fonctions")
     */
    private $contacts;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $secteur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeFonction", inversedBy="fonction")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeFonction;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Humain", inversedBy="fonctions")
     */
    private $humain;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->responsables = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    public function setStructure(?Structure $structure): self
    {
        $this->structure = $structure;

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

    public function getSecteur(): ?string
    {
        return $this->secteur;
    }

    public function setSecteur(?string $secteur): self
    {
        $this->secteur = $secteur;

        return $this;
    }

    public function getTypeFonction(): ?TypeFonction
    {
        return $this->typeFonction;
    }

    public function setTypeFonction(?TypeFonction $typeFonction): self
    {
        $this->typeFonction = $typeFonction;

        return $this;
    }

    public function getHumain(): ?Humain
    {
        return $this->humain;
    }

    public function setHumain(?Humain $humain): self
    {
        $this->humain = $humain;

        return $this;
    }
}
