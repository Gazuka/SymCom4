<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MembreMediathequeRepository")
 */
class MembreMediatheque
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Utilisateur", inversedBy="membreMediatheque", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numCarte;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommandeDriveMediatheque", mappedBy="membreMediatheque", orphanRemoval=true)
     */
    private $commandeDriveMediatheques;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MembreMediatheque", inversedBy="familles")
     */
    private $familles;

    public function __construct()
    {
        $this->commandeDriveMediatheques = new ArrayCollection();
        $this->familles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getNumCarte(): ?string
    {
        return $this->numCarte;
    }

    public function setNumCarte(string $numCarte): self
    {
        $this->numCarte = $numCarte;

        return $this;
    }

    /**
     * @return Collection|CommandeDriveMediatheque[]
     */
    public function getCommandeDriveMediatheques(): Collection
    {
        return $this->commandeDriveMediatheques;
    }

    public function addCommandeDriveMediatheque(CommandeDriveMediatheque $commandeDriveMediatheque): self
    {
        if (!$this->commandeDriveMediatheques->contains($commandeDriveMediatheque)) {
            $this->commandeDriveMediatheques[] = $commandeDriveMediatheque;
            $commandeDriveMediatheque->setMembreMediatheque($this);
        }

        return $this;
    }

    public function removeCommandeDriveMediatheque(CommandeDriveMediatheque $commandeDriveMediatheque): self
    {
        if ($this->commandeDriveMediatheques->contains($commandeDriveMediatheque)) {
            $this->commandeDriveMediatheques->removeElement($commandeDriveMediatheque);
            // set the owning side to null (unless already changed)
            if ($commandeDriveMediatheque->getMembreMediatheque() === $this) {
                $commandeDriveMediatheque->setMembreMediatheque(null);
            }
        }

        return $this;
    }

    public function getCommmandeEnCours()
    {
        return $this->commandeDriveMediatheques[0]; /////////////////////A modifier pour choisir la bonne commande (1 seul commande en cours doit etre possible !)
    }

    /**
     * @return Collection|self[]
     */
    public function getFamilles(): Collection
    {
        return $this->familles;
    }

    public function addFamille(self $famille): self
    {
        if (!$this->familles->contains($famille)) {
            $this->familles[] = $famille;
        }

        return $this;
    }

    public function removeFamille(self $famille): self
    {
        if ($this->familles->contains($famille)) {
            $this->familles->removeElement($famille);
        }

        return $this;
    }
}
