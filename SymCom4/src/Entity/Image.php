<?php

namespace App\Entity;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Media", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $media;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descriptif;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Illustration", mappedBy="image", cascade={"persist", "remove"})
     */
    private $illustration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): self
    {
        $this->media = $media;

        return $this;
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
}
