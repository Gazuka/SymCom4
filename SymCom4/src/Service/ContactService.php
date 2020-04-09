<?php

namespace App\Service;

use App\Entity\Mail;
use Twig\Environment;
use App\Entity\Adresse;
use App\Entity\Telephone;
use App\Form\NewMailType;
use App\Form\NewAdresseType;
use App\Form\NewTelephoneType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class ContactService {
    
    /************************************************************************************/
    public function __construct()
    {
    }
    /************************************************************************************/
    /** Retourne toutes les adresses d'un objet
     *
     * @return void
     */
    public function getAdresses($parent)
    {
        $resultat = array();
        $contacts = $parent->getContacts();
        
        foreach($contacts as $contact)
        {
            if($contact->getType() == 'adresse')
            {
                array_push($resultat, $contact->getAdresse());
            }
        }
        return $resultat;
    }
    /** Retourne tous les telephones d'un objet
     *
     * @return void
     */
    public function getTelephones($parent)
    {
        $resultat = array();
        $contacts = $parent->getContacts();
        
        foreach($contacts as $contact)
        {
            if($contact->getType() == 'telephone')
            {
                array_push($resultat, $contact->getTelephone());
            }
        }
        return $resultat;
    }
    /** Retourne tous les mails d'un objet
     *
     * @return void
     */
   public function getMails($parent)
    {
        $resultat = array();
        $contacts = $parent->getContacts();
        
        foreach($contacts as $contact)
        {
            if($contact->getType() == 'mail')
            {
                array_push($resultat, $contact->getMail());
            }
        }
        return $resultat;
    }

    /** Permet de configurer le formulaire en fonction du type de contact
     *
     * @param [type] $type
     * @return void
     */
    public function addContactConfigForm($type)
    {
        switch($type)
        {
            case 'telephone':
                $tel = new Telephone();
                $variables['classType'] = NewTelephoneType::class;
                $variables['element'] = $tel;
            break;
            case 'mail':
                $mail = new Mail();
                $variables['classType'] = NewMailType::class;
                $variables['element'] = $mail;
            break;
            case 'adresse':
                $adresse = new Adresse();
                $variables['classType'] = NewAdresseType::class;
                $variables['element'] = $adresse;
            break;
        }  

        return $variables;
    }

    /** Permet de configurer le formulaire en fonction du type de contact
     *
     * @param [type] $type
     * @return void
     */
    public function editContactConfigForm($contact)
    {
        $type = $contact->getType();
        switch($type)
        {
            case 'telephone':
                $variables['classType'] = NewTelephoneType::class;
                $variables['element'] = $contact->getTelephone();
            break;
            case 'mail':
                $variables['classType'] = NewMailType::class;
                $variables['element'] = $contact->getMail();
            break;
            case 'adresse':
                $variables['classType'] = NewAdresseType::class;
                $variables['element'] = $contact->getAdresse();
            break;
        }  
        return $variables;
    }
}