<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
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
    private $descriptif;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Illustration", mappedBy="image", cascade={"persist", "remove"})
     */
    private $illustration;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Media", inversedBy="image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $media;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Structure", mappedBy="image")
     */
    private $structures;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Humain", mappedBy="photo", cascade={"persist", "remove"})
     */
    private $humain;

    public function __construct()
    {
        $this->structures = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->media->getTitre();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    public function getIllustration(): ?Illustration
    {
        return $this->illustration;
    }

    public function setIllustration(Illustration $illustration): self
    {
        $this->illustration = $illustration;

        // set the owning side of the relation if necessary
        if ($illustration->getImage() !== $this) {
            $illustration->setImage($this);
        }

        return $this;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(Media $media): self
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return Collection|Structure[]
     */
    public function getStructures(): Collection
    {
        return $this->structures;
    }

    public function addStructure(Structure $structure): self
    {
        if (!$this->structures->contains($structure)) {
            $this->structures[] = $structure;
            $structure->setImage($this);
        }

        return $this;
    }

    public function removeStructure(Structure $structure): self
    {
        if ($this->structures->contains($structure)) {
            $this->structures->removeElement($structure);
            // set the owning side to null (unless already changed)
            if ($structure->getImage() === $this) {
                $structure->setImage(null);
            }
        }

        return $this;
    }

    public function getHumain(): ?Humain
    {
        return $this->humain;
    }

    public function setHumain(?Humain $humain): self
    {
        $this->humain = $humain;

        // set (or unset) the owning side of the relation if necessary
        $newPhoto = null === $humain ? null : $this;
        if ($humain->getPhoto() !== $newPhoto) {
            $humain->setPhoto($newPhoto);
        }

        return $this;
    }
}
