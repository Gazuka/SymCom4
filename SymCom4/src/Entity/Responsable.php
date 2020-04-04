<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResponsableRepository")
 */
class Responsable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Fonction", inversedBy="responsables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fonction;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Humain", inversedBy="responsables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $humain;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFonction(): ?Fonction
    {
        return $this->fonction;
    }

    public function setFonction(?Fonction $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getHumain(): ?Humain
    {
        return $this->humain;
    }

    public function setHumain(?Humain $humain): self
    {
        $this->humain = $humain;

        return $this;
    }
}
