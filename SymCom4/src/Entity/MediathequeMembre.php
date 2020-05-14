<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediathequeMembreRepository")
 * @UniqueEntity(
 *  fields={"numCarte"},
 *  message="Ce numéro de carte est déjà inscrit sur ce service..."
 * )
 * @UniqueEntity(
 *  fields={"utilisateur"},
 *  message="Vous semblez être déjà inscrit sur notre service..."
 * )
 */
class MediathequeMembre
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
     * @Assert\Valid
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numCarte;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MediathequeDriveCommande", mappedBy="membre", orphanRemoval=true)
     */
    private $commandes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MediathequeFamille", inversedBy="adherents", cascade={"persist"})
     */
    private $famille;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
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
     * @return Collection|MediathequeDriveCommande[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function getCommandesByEtat($etat): Array
    {
        $commandes = array();
        foreach($this->commandes as $commande)
        {
            if($commande->getEtat() == $etat)
            {
                $commandes[] = $commande;
            }
        }
        return $commandes;
    }

    public function getOneCommandeByEtat($etat)
    {
        foreach($this->commandes as $commande)
        {
            if($commande->getEtat() == $etat)
            {
                return $commande;
            }
        }
        return null;
    }

    public function getDerniereCommande()
    {
        foreach($this->commandes as $commande)
        {
            if($commande->getEtat() == $etat)
            {
                return $commande;
            }
        }
        return null;
    }

    public function addCommande(MediathequeDriveCommande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setMembre($this);
        }

        return $this;
    }

    public function removeCommande(MediathequeDriveCommande $commande): self
    {
        if ($this->commandes->contains($commande)) {
            $this->commandes->removeElement($commande);
            // set the owning side to null (unless already changed)
            if ($commande->getMembre() === $this) {
                $commande->setMembre(null);
            }
        }

        return $this;
    }

    public function getFamille(): ?MediathequeFamille
    {
        return $this->famille;
    }

    public function setFamille(?MediathequeFamille $famille): self
    {
        $this->famille = $famille;

        return $this;
    }
}
