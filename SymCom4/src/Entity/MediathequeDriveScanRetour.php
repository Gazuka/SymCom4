<?php

namespace App\Entity;

use App\Repository\MediathequeDriveScanRetourRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MediathequeDriveScanRetourRepository::class)
 */
class MediathequeDriveScanRetour
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
    private $codeBarre;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateScan;

    /**
     * @ORM\Column(type="boolean")
     */
    private $traite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeBarre(): ?string
    {
        return $this->codeBarre;
    }

    public function setCodeBarre(string $codeBarre): self
    {
        $this->codeBarre = $codeBarre;

        return $this;
    }

    public function getDateScan(): ?\DateTimeInterface
    {
        return $this->dateScan;
    }

    public function setDateScan(\DateTimeInterface $dateScan): self
    {
        $this->dateScan = $dateScan;

        return $this;
    }

    public function getTraite(): ?bool
    {
        return $this->traite;
    }

    public function setTraite(bool $traite): self
    {
        $this->traite = $traite;

        return $this;
    }
}
