<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CreneauDriveMediathequeRepository")
 */
class CreneauDriveMediatheque
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $debut;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fin;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ouvert;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommandeDriveMediatheque", mappedBy="creneau")
     */
    private $commandes;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    public function getOuvert(): ?bool
    {
        return $this->ouvert;
    }

    public function setOuvert(bool $ouvert): self
    {
        $this->ouvert = $ouvert;

        return $this;
    }

    /**
     * @return Collection|CommandeDriveMediatheque[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(CommandeDriveMediatheque $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setCreneau($this);
        }

        return $this;
    }

    public function removeCommande(CommandeDriveMediatheque $commande): self
    {
        if ($this->commandes->contains($commande)) {
            $this->commandes->removeElement($commande);
            // set the owning side to null (unless already changed)
            if ($commande->getCreneau() === $this) {
                $commande->setCreneau(null);
            }
        }

        return $this;
    }
}
