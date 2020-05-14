<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 * @UniqueEntity(
 *  fields={"pseudo"},
 *  message="Ce pseudo est déjà utilisé..."
 * )
 * @UniqueEntity(
 *  fields={"email"},
 *  message="Cet adresse e-mail est déjà utilisée..."
 * )
 * @UniqueEntity(
 *  fields={"humain"},
 *  message="Vous semblez être déjà inscrit sur notre service..."
 * )
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Humain", inversedBy="utilisateur", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid
     */
    private $humain;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *     message = "L'adresse '{{ value }}' ne semble pas être un e-mail valide..."
     * )
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="utilisateurs", cascade={"persist"})
     */
    private $roles;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\MediathequeMembre", mappedBy="utilisateur", cascade={"persist", "remove"})
     */
    private $membreMediatheque;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHumain(): ?Humain
    {
        return $this->humain;
    }

    public function setHumain(Humain $humain): self
    {
        $this->humain = $humain;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles()
    {
        $roles = $this->roles->map(function($role){
            return $role->getTitre();
        })->toArray();

        $roles[] = 'ROLE_USER';

        return $roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt(){}
    public function eraseCredentials(){}

    public function getUsername()
    {
        return $this->pseudo;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->addUtilisateur($this);
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
            $role->removeUtilisateur($this);
        }

        return $this;
    }

    public function getMembreMediatheque(): ?MediathequeMembre
    {
        return $this->membreMediatheque;
    }

    public function setMembreMediatheque(MediathequeMembre $membreMediatheque): self
    {
        $this->membreMediatheque = $membreMediatheque;

        // set the owning side of the relation if necessary
        if ($membreMediatheque->getUtilisateur() !== $this) {
            $membreMediatheque->setUtilisateur($this);
        }

        return $this;
    }
}
