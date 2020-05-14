<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediathequeDriveCreneauRepository")
 */
class MediathequeDriveCreneau
{
    const DUREE_CRENEAU = 15;
    const DUREE_PREPARATION = 60;
    const FUSEAU_HORAIRE = 120; //On ajoute 2h pour l'heure Française

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
     * @ORM\OneToMany(targetEntity="App\Entity\MediathequeDriveCommande", mappedBy="creneau")
     */
    private $commandes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etat;

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
     * @return Collection|MediathequeDriveCommande[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(MediathequeDriveCommande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setCreneau($this);
        }

        return $this;
    }

    public function removeCommande(MediathequeDriveCommande $commande): self
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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /** Permet de verifier si un creneau doit etre affiché aux visiteurs */
    public function verifEtat(int $iterationCreneau)
    {
        //Vérifie si le creneau est ouvert
        if($this->ouvert == false)
        {
            $this->etat = 'FERME';
        }
        else
        {
            //Vérifie si le creneau est déjà réservé
            if(sizeOf($this->commandes) != 0)
            {
                $this->etat = 'RESERVE';
            }
            else
            {
                //Vérifie si la durée de réservation est raisonnable
                if($this->verifDureePreparation() == false)
                {
                    $this->etat = 'PROCHE';
                }
                else
                {
                    //Vérifie si suffisament de créneau d'ouverture permettent la préparation
                    if($this->verifCreneauxPreparation($iterationCreneau) == false)
                    {
                        $this->etat = 'PROCHE';
                    }
                    else
                    {
                        //Si tout est ok, le créneau est disponible
                        $this->etat = 'DISPONIBLE';
                    }
                }
            }
        }
    }

    private function recupereHeureActuelle()
    {
        //On récupére l'heure actuelle +2h pour correspondre avec le créneau horaire de la Médiathèque
        $now = new DateTime('now');
        $timestamp = $now->getTimestamp();
        $timestamp = strtotime("+ ".$this::FUSEAU_HORAIRE." minutes", $timestamp);
        $now->setTimestamp($timestamp);
        return $now;
    }

    //Vérifie si la durée de réservation est raisonnable
    private function verifDureePreparation()
    {
        //On récupére l'heure actuelle
        $now = $this->recupereHeureActuelle();
        
        //On récupére le timestamp pour ajouter la durée de préparation
        $timestamp = $now->getTimestamp();
        $timestamp = strtotime("+ ".$this::DUREE_PREPARATION." minutes", $timestamp);
        $heureMiniPreparation = new DateTime();
        $heureMiniPreparation->setTimestamp($timestamp);

        if($this->debut < $heureMiniPreparation)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    //Vérifie nbr Créneau de préparation
    private function verifCreneauxPreparation($iterationCreneau)
    {
        if( $iterationCreneau >= ($this::DUREE_PREPARATION / $this::DUREE_CRENEAU) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /** Permet de vérifier si le creneau et passé, s'il est vide ou fermé, on le FINI automatiquement */
    public function verifCreneauPasse()
    {
        //On récupére l'heure actuelle
        $now = $this->recupereHeureActuelle();

        //Si le creneau est passé et different de FINI
        if($this->fin < $now && $this->etat != 'FINI')
        {
            if($this->ouvert == false)
            {
                $this->etat = 'FINI';
            }
            else
            {
                if(sizeOf($this->commandes) == 0)
                {
                    $this->etat = 'FINI';
                }
            }
        }
    }
}
