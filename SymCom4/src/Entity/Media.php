<?php

namespace App\Entity;

use DateTime;
use App\Entity\Image;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 */
class Media
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dossier", inversedBy="media")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dossier;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $extension;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $public;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", mappedBy="media", cascade={"persist", "remove"})
     */
    private $image;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDossier(): ?Dossier
    {
        return $this->dossier;
    }

    public function setDossier(?Dossier $dossier): self
    {
        $this->dossier = $dossier;

        return $this;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(?bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(Image $image): self
    {
        $this->image = $image;

        // set the owning side of the relation if necessary
        if ($image->getMedia() !== $this) {
            $image->setMedia($this);
        }

        return $this;
    }

    public function upload($dossier)
    {
        $fichier = $this->getNom();
        $this->setDossier($dossier);
        $this->nom = strtolower($fichier->getClientOriginalName());
        $this->extension = $fichier->getClientOriginalExtension();
        $now = new DateTime(); 
        $now->format('Y-m-d H:i:s'); 
        $this->date = $now;
        if($this->titre == null)
        {
            $this->titre = $this->nom;
        } 

        switch($this->extension)
        {
            case 'jpg':
            case 'jpeg':
                //On enregistre une image
                $image = new Image();
                $image->setDescriptif("...");
                $this->setImage($image);
                $this->setDossier($dossier);
                
            break;
            case 'pdf':
                //On enregistre un pdf
            break;
            default:
                //Type de fichier inconnu
            break;
        }

        $this->nom = $now->getTimestamp().$this->nom;
        $fichier->move(str_replace ( "/", "\\", getcwd()."\medias\\".$this->dossier->getChemin()), $this->nom);
    }

    public function recupAsset()
    {
        return 'medias/'.$this->dossier->getChemin().$this->nom;
    }

    public function deplacer($dossier)
    {
        //On créer un fichier Filesystem
        $filesystem = new Filesystem();
        //On récupére le dossier actuel du fichier
        $dossierActuel = $this->dossier->getChemin();
        //On récupére le chemin actuel du fichier
        $fichier = $this->recupAsset();
        //On défini le nouveau dossier
        $this->setDossier($dossier);
        //On copy le fichier puis on le supprime
        // dump($fichier);
        // dump("medias/".$this->dossier->getChemin().$this->nom);
        // dd('oups');
        $filesystem->copy($fichier, "medias/".$this->dossier->getChemin().$this->nom);
        $filesystem->remove($fichier, $dossierActuel);
    }
}
