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
    
    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** FONCTIONS MAGIQUES ********************************************************************/

    public function __construct()
    {
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** FONCTIONS GET ET SET ******************************************************************/

    /**
     * Retourne toutes les adresses d'un objet
     *
     * @param Object $parent
     * @return Array
     */
    public function getAdresses(Object $parent):Array
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

    /**
     * Retourne tous les telephones d'un objet
     *
     * @param Object $parent
     * @return Array
     */
    public function getTelephones(Object $parent):Array
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

    /**
     * Retourne tous les mails d'un objet
     *
     * @param Object $parent
     * @return Array
     */
    public function getMails(Object $parent):Array
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

    /**
     * Retourner un objet qui correspond au type demandé
     *
     * @param string $type
     * @param Object $contact
     * @return Object
     */
    public function getElementFormulaire(string $type, Object $contact = null):Object
    {
        switch($type)
        {
            case 'telephone':
                if($contact != null)
                {
                    $element = $contact->getTelephone();
                }
                else
                {
                    $element = new Telephone();
                }
            break;
            case 'mail':
                if($contact != null)
                {
                    $element = $contact->getMail();
                }
                else
                {
                    $element = new Mail();
                }
            break;
            case 'adresse':
                if($contact != null)
                {
                    $element = $contact->getAdresse();
                }
                else
                {
                    $element = new Adresse();
                }
            break;
        }
        return $element;
    }

    /**
     * Retourner la classType qui correspond au type demandé
     *
     * @param string $type
     * @return string
     */
    public function getClassTypeFormulaire(string $type):string
    {
        switch($type)
        {
            case 'telephone':
                $classType = NewTelephoneType::class;
            break;
            case 'mail':
                $classType = NewMailType::class;
            break;
            case 'adresse':
                $classType = NewAdresseType::class;
            break;
        }
        return $classType;
    }
}