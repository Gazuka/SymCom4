<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediathequeDriveCommandeEtatRepository")
 */
class MediathequeDriveCommandeEtat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MediathequeDriveCommande", inversedBy="etats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(type="boolean")
     */
    private $bloquant;

    public function __construct($etat)
    {
        //On récupére l'heure actuelle +2h pour correspondre avec le créneau horaire de la Médiathèque
        $now = new DateTime('now');
        $timestamp = $now->getTimestamp();
        $timestamp = strtotime("+ 120 minutes", $timestamp);
        $now->setTimestamp($timestamp);

        $this->etat = $etat;
        $this->date = $now;
        
        $this->ifBloquant();
    }

    private function ifBloquant()
    {
        switch($this->etat)
        {
            case 'USER_ENCOURS' :
                $this->bloquant = false;
            break;
            default:
                $this->bloquant = true;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommande(): ?MediathequeDriveCommande
    {
        return $this->commande;
    }

    public function setCommande(?MediathequeDriveCommande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        $this->ifBloquant();
        
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getBloquant(): ?bool
    {
        return $this->bloquant;
    }

    public function setBloquant(bool $bloquant): self
    {
        $this->bloquant = $bloquant;

        return $this;
    }
}
