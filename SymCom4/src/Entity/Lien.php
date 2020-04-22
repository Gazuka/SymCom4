<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LienRepository")
 */
class Lien
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
    private $label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fontAwesome;

    /**
     * @ORM\Column(type="boolean")
     */
    private $extern;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $colorBoot;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Page", inversedBy="liens")
     */
    private $page;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Structure", mappedBy="lien", cascade={"persist"})
     */
    private $structure;

    public function __construct()
    {
        $this->extern = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getFontAwesome(): ?string
    {
        return $this->fontAwesome;
    }

    public function setFontAwesome(?string $fontAwesome): self
    {
        $this->fontAwesome = $fontAwesome;

        return $this;
    }

    public function getExtern(): ?bool
    {
        return $this->extern;
    }

    public function setExtern(bool $extern): self
    {
        $this->extern = $extern;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        if($this->label == null || $this->label == $this->url)
        {
            $this->label = $url;
        }
        $this->url = $url;
        return $this;
    }

    public function getColorBoot(): ?string
    {
        return $this->colorBoot;
    }

    public function setColorBoot(?string $colorBoot): self
    {
        $this->colorBoot = $colorBoot;

        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    public function setStructure(?Structure $structure): self
    {
        $this->structure = $structure;

        // set (or unset) the owning side of the relation if necessary
        $newLien = null === $structure ? null : $this;
        if ($structure->getLien() !== $newLien) {
            $structure->setLien($newLien);
        }

        return $this;
    }
}
