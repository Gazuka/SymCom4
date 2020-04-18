<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class LienBoutonExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('LienBouton', [$this, 'afficheLienBouton'], ['is_safe' => ['html', 'twig']]),
            new TwigFilter('ModifierBouton', [$this, 'afficheModifierBouton'], ['is_safe' => ['html', 'twig']]),
            new TwigFilter('SupprimerBouton', [$this, 'afficheSupprimerBouton'], ['is_safe' => ['html', 'twig']]),
            new TwigFilter('BtnSubmit', [$this, 'afficheBtnSubmit'], ['is_safe' => ['html', 'twig']])
        ];
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

    public function afficheModifierBouton($url)
    {
        $html = "<a class='btn btn-primary' href='".$url."'><i class='fas fa-edit'></i> Modifier</a>";
        return $html;
    }

    /** Affiche un bouton de suppression avec un modal avant de rediriger vers la page de suppression
     *
     * @param [string] $url //L'url de la page de suppression
     * @param string $text //Texte à ajouter (nom de l'objet à supprimer)
     * @return void
     */
    public function afficheSupprimerBouton($url, $text = '')
    {
        if($text != '')
        {
            $text = ': '.$text;
        }
        $idUnique = rand(0, 500000);
        $html = "";
        $html .= "<button type='button' class='btn btn-danger' data-toggle='modal' data-target='#modal_".$idUnique."'>";
        $html .= "<i class='fas fa-trash'></i> Supprimer ".$text."</a>";
        $html .= "</button>";
        $html .= "<div class='modal fade' id='modal_".$idUnique."' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
        $html .= " <div class='modal-dialog' role='document'>";
        $html .= "  <div class='modal-content'>";
        $html .= "   <div class='modal-header'>";
        $html .= "    <h5 class='modal-title' id='exampleModalLabel'>Attention, suppression définitive !</h5>";
        $html .= "    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
        $html .= "     <span aria-hidden='true'>&times;</span>";
        $html .= "    </button>";
        $html .= "   </div>";
        $html .= "   <div class='modal-body'>";
        $html .= "    Dernière chance, êtes-vous bien certain de vouloir supprimer ".$text." ?";
        $html .= "   </div>";
        $html .= "   <div class='modal-footer'>";
        $html .= "    <button type='button' class='btn btn-primary' data-dismiss='modal'>Annuler</button>";
        $html .= "    <a class='btn btn-danger' href='".$url."'><i class='fas fa-trash'></i> Supprimer ".$text."</a>";
        $html .= "   </div>";
        $html .= "  </div>";
        $html .= " </div>";
        $html .= "</div>";
        return $html;
    }

    public function afficheBtnSubmit($text)
    {
        return "<button type='submit' class='btn btn-success'><i class='fas fa-check'></i> ".$text."</button>";
    }
}