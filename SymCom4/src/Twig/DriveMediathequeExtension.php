<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class DriveMediathequeExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('DateComplete', [$this, 'afficheDateComplete'], ['is_safe' => ['html', 'twig']]),
            new TwigFilter('ModifierBouton', [$this, 'afficheModifierBouton'], ['is_safe' => ['html', 'twig']]),
            new TwigFilter('SupprimerBouton', [$this, 'afficheSupprimerBouton'], ['is_safe' => ['html', 'twig']]),
            new TwigFilter('BtnSubmit', [$this, 'afficheBtnSubmit'], ['is_safe' => ['html', 'twig']])
        ];
    }

    public function afficheDateComplete($date)
    {
        $semaine = $date->format('l');
        $jour = $date->format('j');
        $mois = $date->format('m');
        $annee = $date->format('Y');

        switch($semaine)
        {
            case 'Monday':
                $semaine = 'Lundi';
            break;
            case 'Tuesday':
                $semaine = 'Mardi';
            break;
            case 'Wednesday':
                $semaine = 'Mercredi';
            break;
            case 'Thursday':
                $semaine = 'Jeudi';
            break;
            case 'Friday':
                $semaine = 'Vendredi';
            break;
            case 'Saturday':
                $semaine = 'Samedi';
            break;
            case 'Sunday':
                $semaine = 'Dimanche';
            break;                  
        }

        switch($mois)
        {
            case '01':
                $mois = 'janvier';
            break;
            case '02':
                $mois = 'février';
            break;
            case '03':
                $mois = 'mars';
            break;
            case '04':
                $mois = 'avril';
            break;
            case '05':
                $mois = 'mai';
            break;
            case '06':
                $mois = 'juin';
            break;
            case '07':
                $mois = 'juillet';
            break;
            case '08':
                $mois = 'août';
            break;
            case '09':
                $mois = 'septembre';
            break;
            case '10':
                $mois = 'octobre';
            break;
            case '11':
                $mois = 'novembre';
            break;
            case '12':
                $mois = 'décembre';
            break;
        }


       return $semaine.' '.$jour.' '.$mois.' '.$annee; 
    }
}