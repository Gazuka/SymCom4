<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HumainRepository")
 * @UniqueEntity(
 *      fields={"nom", "prenom"},
 *      message="Cet personne est déjà identifiée sur le site."
 * )
 * 
 * @ORM\HasLifecycleCallbacks
 */
class Humain
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 3, max = 100, minMessage = "Le nom doit comporter au minimum 5 caractères.", maxMessage = "Le nom doit comporter au maximum 100 caractères.")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Contact", inversedBy="humains")
     */
    private $contacts;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", inversedBy="humain", cascade={"persist", "remove"})
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $sexe;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Fonction", mappedBy="humain")
     */
    private $fonctions;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Utilisateur", mappedBy="humain", cascade={"persist", "remove"})
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateNaissance;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->responsables = new ArrayCollection();
        $this->fonctions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom." ".$this->prenom;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
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

    public function getPhoto(): ?Image
    {
        return $this->photo;
    }

    public function setPhoto(?Image $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

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
            $fonction->setHumain($this);
        }

        return $this;
    }

    public function removeFonction(Fonction $fonction): self
    {
        if ($this->fonctions->contains($fonction)) {
            $this->fonctions->removeElement($fonction);
            // set the owning side to null (unless already changed)
            if ($fonction->getHumain() === $this) {
                $fonction->setHumain(null);
            }
        }

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        // set the owning side of the relation if necessary
        if ($utilisateur->getHumain() !== $this) {
            $utilisateur->setHumain($this);
        }

        return $this;
    }

    /**
     * Permet d'initialiser le slug
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function initSlug():void
    {
        if(empty($this->slug))
        {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->nom." ".$this->prenom);
        }
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }
}
