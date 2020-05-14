<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediathequeFamilleRepository")
 */
class MediathequeFamille
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MediathequeMembre", mappedBy="famille")
     */
    private $adherents;

    public function __construct()
    {
        $this->adherents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|MediathequeMembre[]
     */
    public function getAdherents(): Collection
    {
        return $this->adherents;
    }

    public function addAdherent(MediathequeMembre $adherent): self
    {
        if (!$this->adherents->contains($adherent)) {
            $this->adherents[] = $adherent;
            $adherent->setFamille($this);
        }

        return $this;
    }

    public function removeAdherent(MediathequeMembre $adherent): self
    {
        if ($this->adherents->contains($adherent)) {
            $this->adherents->removeElement($adherent);
            // set the owning side to null (unless already changed)
            if ($adherent->getFamille() === $this) {
                $adherent->setFamille(null);
            }
        }

        return $this;
    }
}
