<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class ContactsExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
                    new TwigFilter('NbrContacts', [$this, 'afficheNbrContacts'], ['is_safe' => ['html', 'twig']]),
                    new TwigFilter('AddContacts', [$this, 'afficheAddContacts'], ['is_safe' => ['html', 'twig']]),
                    new TwigFilter('AddLien', [$this, 'afficheAddLien'], ['is_safe' => ['html', 'twig']])
                ];
    }

    public function afficheNbrContacts($contacts)
    {
        $telephone = 0;
        $mail = 0;
        $adresse = 0;
        
        foreach($contacts as $contact)
        {
            switch($contact->getType())
            {
                case 'mail':
                    $mail = $mail + 1 ;
                break;
                case 'telephone':
                    $telephone = $telephone + 1 ;
                break;
                case 'adresse':
                    $adresse = $adresse + 1 ;
                break;
            }
        }
        if(sizeof($contacts) == 0)
        {
            $html = "<div class='text-danger'>Pas de contacts...</p>";
        }
        else
        {
            $html = "<div><i class='fas fa-phone'></i> x ".$telephone." / <i class='fas fa-at'></i> x ".$mail." / <i class='fas fa-home'></i> x ".$adresse."</div>";
        
        }
        return $html;                            
    }

    public function afficheAddContacts($url, $var, $texte = "")
    {
        $html = "";
        switch($var)
        {
            case 'telephone':
                $html = "<a class='btn btn-success' href='".$url."'><i class='fas fa-plus'></i> <i class='fas fa-phone'></i> ".$texte."</a>";
            break;
            case 'mail':
                $html = "<a class='btn btn-success' href='".$url."'><i class='fas fa-plus'></i> <i class='fas fa-at'></i> ".$texte."</a>";
            break;
            case 'adresse':
                $html = "<a class='btn btn-success' href='".$url."'><i class='fas fa-plus'></i> <i class='fas fa-home'></i> ".$texte."</a>";
            break;
        }
        return $html;
    }

    public function afficheAddLien($url, $texte = "")
    {
        $html = "<a class='btn btn-success' href='".$url."'><i class='fas fa-plus'></i> <i class='fas fa-phone'></i> ".$texte."</a>";
        return $html;
    }
}