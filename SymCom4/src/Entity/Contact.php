<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $prive;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Adresse", mappedBy="contact", cascade={"persist", "remove"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Mail", mappedBy="contact", cascade={"persist", "remove"})
     */
    private $mail;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Telephone", mappedBy="contact", cascade={"persist", "remove"})
     */
    private $telephone;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Lieu", mappedBy="contacts")
     */
    private $lieux;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Structure", mappedBy="contacts")
     */
    private $structures;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Fonction", mappedBy="contacts")
     */
    private $fonctions;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Groupe", mappedBy="contacts")
     */
    private $groupes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Humain", mappedBy="contacts")
     */
    private $humains;

    public function __construct()
    {
        $this->lieux = new ArrayCollection();
        $this->structures = new ArrayCollection();
        $this->fonctions = new ArrayCollection();
        $this->groupes = new ArrayCollection();
        $this->humains = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrive(): ?bool
    {
        return $this->prive;
    }

    public function setPrive(bool $prive): self
    {
        $this->prive = $prive;

        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(Adresse $adresse): self
    {
        $this->adresse = $adresse;

        // set the owning side of the relation if necessary
        if ($adresse->getContact() !== $this) {
            $adresse->setContact($this);
        }

        return $this;
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

    public function getMail(): ?Mail
    {
        return $this->mail;
    }

    public function setMail(Mail $mail): self
    {
        $this->mail = $mail;

        // set the owning side of the relation if necessary
        if ($mail->getContact() !== $this) {
            $mail->setContact($this);
        }

        return $this;
    }

    public function getTelephone(): ?Telephone
    {
        return $this->telephone;
    }

    public function setTelephone(Telephone $telephone): self
    {
        $this->telephone = $telephone;

        // set the owning side of the relation if necessary
        if ($telephone->getContact() !== $this) {
            $telephone->setContact($this);
        }

        return $this;
    }

    /**
     * @return Collection|Lieu[]
     */
    public function getLieux(): Collection
    {
        return $this->lieux;
    }

    public function addLieux(Lieu $lieux): self
    {
        if (!$this->lieux->contains($lieux)) {
            $this->lieux[] = $lieux;
            $lieux->addContact($this);
        }

        return $this;
    }

    public function removeLieux(Lieu $lieux): self
    {
        if ($this->lieux->contains($lieux)) {
            $this->lieux->removeElement($lieux);
            $lieux->removeContact($this);
        }

        return $this;
    }

    /**
     * @return Collection|Structure[]
     */
    public function getStructures(): Collection
    {
        return $this->structures;
    }

    public function addStructure(Structure $structure): self
    {
        if (!$this->structures->contains($structure)) {
            $this->structures[] = $structure;
            $structure->addContact($this);
        }

        return $this;
    }

    public function removeStructure(Structure $structure): self
    {
        if ($this->structures->contains($structure)) {
            $this->structures->removeElement($structure);
            $structure->removeContact($this);
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
            $fonction->addContact($this);
        }

        return $this;
    }

    public function removeFonction(Fonction $fonction): self
    {
        if ($this->fonctions->contains($fonction)) {
            $this->fonctions->removeElement($fonction);
            $fonction->removeContact($this);
        }

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
            $groupe->addContact($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->contains($groupe)) {
            $this->groupes->removeElement($groupe);
            $groupe->removeContact($this);
        }

        return $this;
    }

    /**
     * @return Collection|Humain[]
     */
    public function getHumains(): Collection
    {
        return $this->humains;
    }

    public function addHumain(Humain $humain): self
    {
        if (!$this->humains->contains($humain)) {
            $this->humains[] = $humain;
            $humain->addContact($this);
        }

        return $this;
    }

    public function removeHumain(Humain $humain): self
    {
        if ($this->humains->contains($humain)) {
            $this->humains->removeElement($humain);
            $humain->removeContact($this);
        }

        return $this;
    }
}
