<?php

namespace App\Service;

use DateTime;
use DateTimeZone;
use App\Entity\MediathequeDriveCreneau;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MediathequeDriveService {
    
    //Reçus dans le constructeur
    private $manager;               //Objet EntityManagerInterface

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** FONCTIONS MAGIQUES ********************************************************************/

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** FONCTIONS GET ET SET ******************************************************************/
    
    
    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** FONCTIONS PUBLIQUES *******************************************************************/
    
    public function creerCreneauxJournee($jour, $mois, $annee, $heure, $minute)
    {
        $premier = new DateTime($annee.'-'.$mois.'-'.$jour.'T'.$heure.':'.$minute.':00');
        $debut = $premier;
        do
        {
            //On récupére le timestamp pour ajouter 15 minutes
            $timestamp = $debut->getTimestamp();
            $timestamp = strtotime("+ 15 minutes", $timestamp);
            //On converti le timestamp en datetime
            $fin = new DateTime();
            $fin->setTimestamp($timestamp);

            //On crée l'objet avec date de debut et de fin
            $this->creerCreneau($debut, $fin);
            
            //La date de fin sera celle de début pour le prochain créneau
            $debut = $fin;
            $heure = $debut->format('H');
        }
        while($heure < 18);  
    }

    // public function dansUneHeure($now)
    // {
    //     //On récupére le timestamp pour ajouter 60 minutes
    //     $timestamp = $now->getTimestamp();
    //     $timestamp = strtotime("+ 60 minutes", $timestamp);
    //     //On converti le timestamp en datetime
    //     $dansUneHeure = new DateTime();
    //     $dansUneHeure->setTimestamp($timestamp); 
    //     return $dansUneHeure; 
    // }

    
    

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** FONCTIONS PRIVES **********************************************************************/

    private function creerCreneau($debut, $fin)
    {
        $creneau = new MediathequeDriveCreneau();
        $creneau->setDebut($debut);
        $creneau->setFin($fin);
        $creneau->setOuvert($this->verifOuverture($debut, $fin));
        $this->manager->persist($creneau);
    }

    private function verifOuverture($debut, $fin)
    {
        $ouvert = true;

        //Si on est lundi ou dimanche, c'est fermé
        if($debut->format('l') == 'Monday' || $debut->format('l') == 'Sunday')
        {
            $ouvert = false;
        }
        else
        {
            //Si le début est avant 9h, c'est fermé
            if($debut->format('H') < 9)
            {
                $ouvert = false;
            }
            else
            {
                //Si le début est entre 12h et 13h, c'est fermé
                if($debut->format('H') == 12)
                {
                    $ouvert = false;
                }
                else
                {
                    //Si le début est avant 13h30, c'est fermé
                    if($debut->format('H') == 13 && $debut->format('i') < 30)
                    {
                        $ouvert = false;
                    }
                    else
                    {
                        //Si le début est entre 13h30 et 14h, c'est fermé sauf le mardi
                        if($debut->format('H') == 13 && $debut->format('i') >= 30)
                        {
                            //Si on n'est pas mardi, c'est fermé
                            if($debut->format('l') != 'Tuesday')
                            {
                                $ouvert = false;
                            }
                        }
                        else
                        {
                            //si on est jeudi ou samedi, si la fin est après 17h, c'est fermé
                            if($fin->format('l') == 'Thursday' || $fin->format('l') == 'Saturday')
                            {
                                if(($fin->format('H') == 17 && $fin->format('i') >= 1) || $fin->format('H') > 17)
                                {
                                    $ouvert = false;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $ouvert;
    }
}