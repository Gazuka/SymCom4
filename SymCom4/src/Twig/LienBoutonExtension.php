<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class LienBoutonExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [new TwigFilter('LienBouton', [$this, 'afficheLienBouton'], ['is_safe' => ['html', 'twig']])];
    }

    public function afficheLienBouton($lien)
    {
        //On cherche la bonne couleur
        if($lien->getColorBoot() != null)
        {
            $couleur = $lien->getColorBoot();
        }
        else
        {
            $couleur = 'primary';
        }
        //On cherche le logo
        if($lien->getFontAwesome() != null)
        {
            $logo = "<i class='".$lien->getFontAwesome()."'></i>";
        }
        else
        {
            $logo = '';
        }
        //On cherche le label
        $label = $lien->getLabel();
        //On cherche l'url
        if($lien->getPage() != null)
        {
            $url = $lien->getPage()->getUrl();
        }
        else
        {
            if($label->getUrl() != null)
            {
                $url = $lien->getUrl();
            }  
            else
            {
                $url = "#";
            }
        }

        $html = "<a class='btn btn-%s' href='%s'>%s %s</a>";
        
        return \sprintf($html, $couleur, $url, $logo, $label);
    }
}