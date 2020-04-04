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
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titreFeminin;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Responsable", mappedBy="fonction", orphanRemoval=true)
     */
    private $responsables;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->responsables = new ArrayCollection();
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

    /**
     * @return Collection|Responsable[]
     */
    public function getResponsables(): Collection
    {
        return $this->responsables;
    }

    public function addResponsable(Responsable $responsable): self
    {
        if (!$this->responsables->contains($responsable)) {
            $this->responsables[] = $responsable;
            $responsable->setFonction($this);
        }

        return $this;
    }

    public function removeResponsable(Responsable $responsable): self
    {
        if ($this->responsables->contains($responsable)) {
            $this->responsables->removeElement($responsable);
            // set the owning side to null (unless already changed)
            if ($responsable->getFonction() === $this) {
                $responsable->setFonction(null);
            }
        }

        return $this;
    }
}
