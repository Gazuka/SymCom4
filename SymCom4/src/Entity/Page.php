<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 */
class Page
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
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomChemin;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descriptif;

    /**
     * @ORM\Column(type="array")
     */
    private $params = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lien", mappedBy="page")
     */
    private $liens;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    public function __construct()
    {
        $this->liens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNomChemin(): ?string
    {
        return $this->nomChemin;
    }

    public function setNomChemin(string $nomChemin): self
    {
        $this->nomChemin = $nomChemin;

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(?string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    public function getParams(): ?array
    {
        return $this->params;
    }

    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return Collection|Lien[]
     */
    public function getLiens(): Collection
    {
        return $this->liens;
    }

    public function addLien(Lien $lien): self
    {
        if (!$this->liens->contains($lien)) {
            $this->liens[] = $lien;
            $lien->setPage($this);
        }

        return $this;
    }

    public function removeLien(Lien $lien): self
    {
        if ($this->liens->contains($lien)) {
            $this->liens->removeElement($lien);
            // set the owning side to null (unless already changed)
            if ($lien->getPage() === $this) {
                $lien->setPage(null);
            }
        }

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
