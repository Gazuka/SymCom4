<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediathequeDriveCommandeRepository")
 */
class MediathequeDriveCommande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 5,
     *      notInRangeMessage = "La valeur doit être comprise entre {{ min }} et {{ max }} !"
     * )
     */
    private $nbrLivreChoisi = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 4,
     *      notInRangeMessage = "La valeur doit être comprise entre {{ min }} et {{ max }} !"
     * )
     */
    private $nbrCdChoisi = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 2,
     *      notInRangeMessage = "La valeur doit être comprise entre {{ min }} et {{ max }} !"
     * )
     */
    private $nbrDvdChoisi = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 5,
     *      notInRangeMessage = "La valeur doit être comprise entre {{ min }} et {{ max }} !"
     * )
     */
    private $nbrLivreSurprise = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 4,
     *      notInRangeMessage = "La valeur doit être comprise entre {{ min }} et {{ max }} !"
     * )
     */
    private $nbrCdSurprise = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 2,
     *      notInRangeMessage = "La valeur doit être comprise entre {{ min }} et {{ max }} !"
     * )
     */
    private $nbrDvdSurprise = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MediathequeMembre", inversedBy="commandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MediathequeDriveCreneau", inversedBy="commandes")
     */
    private $creneau;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archive;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MediathequeDriveCommandeEtat", mappedBy="commande", cascade={"persist", "remove"})
     */
    private $etats;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $retourLivre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $retourCD;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $retourDVD;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $noteInterne;

    public function __construct()
    {
        $this->etats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrLivreChoisi(): ?int
    {
        return $this->nbrLivreChoisi;
    }

    public function setNbrLivreChoisi(int $nbrLivreChoisi): self
    {
        $this->nbrLivreChoisi = $nbrLivreChoisi;

        return $this;
    }

    public function getNbrCdChoisi(): ?int
    {
        return $this->nbrCdChoisi;
    }

    public function setNbrCdChoisi(int $nbrCdChoisi): self
    {
        $this->nbrCdChoisi = $nbrCdChoisi;

        return $this;
    }

    public function getNbrDvdChoisi(): ?int
    {
        return $this->nbrDvdChoisi;
    }

    public function setNbrDvdChoisi(int $nbrDvdChoisi): self
    {
        $this->nbrDvdChoisi = $nbrDvdChoisi;

        return $this;
    }

    public function getNbrLivreSurprise(): ?int
    {
        return $this->nbrLivreSurprise;
    }

    public function setNbrLivreSurprise(int $nbrLivreSurprise): self
    {
        $this->nbrLivreSurprise = $nbrLivreSurprise;

        return $this;
    }

    public function getNbrCdSurprise(): ?int
    {
        return $this->nbrCdSurprise;
    }

    public function setNbrCdSurprise(int $nbrCdSurprise): self
    {
        $this->nbrCdSurprise = $nbrCdSurprise;

        return $this;
    }

    public function getNbrDvdSurprise(): ?int
    {
        return $this->nbrDvdSurprise;
    }

    public function setNbrDvdSurprise(int $nbrDvdSurprise): self
    {
        $this->nbrDvdSurprise = $nbrDvdSurprise;

        return $this;
    }

    public function getMembre(): ?MediathequeMembre
    {
        return $this->membre;
    }

    public function setMembre(?MediathequeMembre $membre): self
    {
        $this->membre = $membre;

        return $this;
    }

    public function getCreneau(): ?MediathequeDriveCreneau
    {
        return $this->creneau;
    }

    public function setCreneau(?MediathequeDriveCreneau $creneau): self
    {
        $this->creneau = $creneau;

        return $this;
    }

    public function getArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): self
    {
        $this->archive = $archive;

        return $this;
    }

    public function getEtats()
    {
        return $this->etats;
    }

    public function setEtats(?string $etats): self
    {
        $this->etats = $etats;

        return $this;
    }

    public function addEtat(MediathequeDriveCommandeEtat $etat): self
    {
        if (!$this->etats->contains($etat)) {
            $this->etats[] = $etat;
            $etat->setCommande($this);
        }

        return $this;
    }

    public function removeEtat(MediathequeDriveCommandeEtat $etat): self
    {
        if ($this->etats->contains($etat)) {
            $this->etats->removeElement($etat);
            // set the owning side to null (unless already changed)
            if ($etat->getCommande() === $this) {
                $etat->setCommande(null);
            }
        }

        return $this;
    }

    //Retourne le dernier état
    public function getEtat()
    {
        return $this->etats->last();
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getRetourLivre(): ?int
    {
        return $this->retourLivre;
    }

    public function setRetourLivre(?int $retourLivre): self
    {
        $this->retourLivre = $retourLivre;

        return $this;
    }

    public function getRetourCD(): ?int
    {
        return $this->retourCD;
    }

    public function setRetourCD(?int $retourCD): self
    {
        $this->retourCD = $retourCD;

        return $this;
    }

    public function getRetourDVD(): ?int
    {
        return $this->retourDVD;
    }

    public function setRetourDVD(?int $retourDVD): self
    {
        $this->retourDVD = $retourDVD;

        return $this;
    }

    public function getNoteInterne(): ?string
    {
        return $this->noteInterne;
    }

    public function setNoteInterne(?string $noteInterne): self
    {
        $this->noteInterne = $noteInterne;

        return $this;
    }
}
