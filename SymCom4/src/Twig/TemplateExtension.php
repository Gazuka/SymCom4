<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TemplateExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return array(
            // on déclare notre fonction.
            // Le 1er paramètre est le nom de la fonction utilisée dans notre template
            // le 2ème est un tableau dont le 1er élément représente la classe où trouver la fonction associée (en l'occurence $this, c'est à dire cette classe puisque notre fonction est déclarée un peu plus bas). Et le 2ème élément du tableau est le nom de la fonction associée qui sera appelée lorsque nous l'utiliserons dans notre template.
            new TwigFunction('taille', array($this, 'taille')),
        );
    }

    // chemin relatif de notre fichier en paramètre
    public function taille(int $xl, int $md)
    {
        return "col-xl-".$xl." col-md-".$md;
    }
}