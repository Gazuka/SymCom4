<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandeDriveMediathequeRepository")
 */
class CommandeDriveMediatheque
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
    private $nbrLivreChoisi;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 4,
     *      notInRangeMessage = "La valeur doit être comprise entre {{ min }} et {{ max }} !"
     * )
     */
    private $nbrCdChoisi;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 2,
     *      notInRangeMessage = "La valeur doit être comprise entre {{ min }} et {{ max }} !"
     * )
     */
    private $nbrDvdChoisi;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 5,
     *      notInRangeMessage = "La valeur doit être comprise entre {{ min }} et {{ max }} !"
     * )
     */
    private $nbrLivreSurprise;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 4,
     *      notInRangeMessage = "La valeur doit être comprise entre {{ min }} et {{ max }} !"
     * )
     */
    private $nbrCdSurprise;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 2,
     *      notInRangeMessage = "La valeur doit être comprise entre {{ min }} et {{ max }} !"
     * )
     */
    private $nbrDvdSurprise;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MembreMediatheque", inversedBy="commandeDriveMediatheques")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membreMediatheque;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CreneauDriveMediatheque", inversedBy="commandes")
     */
    private $creneau;

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

    public function getMembreMediatheque(): ?MembreMediatheque
    {
        return $this->membreMediatheque;
    }

    public function setMembreMediatheque(?MembreMediatheque $membreMediatheque): self
    {
        $this->membreMediatheque = $membreMediatheque;

        return $this;
    }

    public function getCreneau(): ?CreneauDriveMediatheque
    {
        return $this->creneau;
    }

    public function setCreneau(?CreneauDriveMediatheque $creneau): self
    {
        $this->creneau = $creneau;

        return $this;
    }
}
