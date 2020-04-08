<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class ContactService {
    //private $entityClass;
    //private $manager;
    private $twig;
    private $parent; //Le parent des contacts
    private $idPage; //L'id de la page de base pour eventuellement y revenir par la suite

    /*public function __construct(ObjectManager $manager, Environment $twig, RequestStack $request)
    {
        $this->manager = $manager;
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
    }*/

    public function __construct(Environment $twig, RequestStack $request)
    {
        $this->twig = $twig;
        $this->routeActuelle = $request->getCurrentRequest()->attributes->get('_route');
    }

    public function setIdPage($id)
    {
        $this->idPage = $id;
    }
    public function getParent()
    {
        return $this->parent;
    }
    public function setParent($object)
    {
        $this->parent = $object;
    }
    /***********************************/

    /** Affiche dans TWIG un tableau pour voir les adresses, les modifier ou en ajouter
     *
     * @return void
     */
    public function gestionAdresses()
    {
        $this->twig->display('symcom4/admin/gestion/_gestion_adresses.html.twig',
        [
            'parent' => $this->parent,
            'adresses' => $this->getAdresses(),
            'idPage' => $this->idPage
        ]); 
    }
    /** Affiche dans TWIG un tableau pour voir les e-mails, les modifier ou en ajouter
     *
     * @return void
     */
    public function gestionMails()
    {
        $this->twig->display('symcom4/admin/gestion/_gestion_mails.html.twig',
        [
            'parent' => $this->parent,
            'mails' => $this->getMails(),
            'idPage' => $this->idPage
        ]); 
    }
    /** Affiche dans TWIG un tableau pour voir les téléphones, les modifier ou en ajouter
     *
     * @return void
     */
    public function gestionTelephones()
    {
        $this->twig->display('symcom4/admin/gestion/_gestion_telephones.html.twig',
        [
            'parent' => $this->parent,
            'telephones' => $this->getTelephones(),
            'idPage' => $this->idPage
        ]); 
    }
    
    

    public function getAdresses()
    {
        $resultat = array();
        $contacts = $this->parent->getContacts();
        
        foreach($contacts as $contact)
        {
            if($contact->getType() == 'adresse')
            {
                array_push($resultat, $contact->getAdresse());
            }
        }
        return $resultat;
    }
    public function getTelephones()
    {
        $resultat = array();
        $contacts = $this->parent->getContacts();
        
        foreach($contacts as $contact)
        {
            if($contact->getType() == 'telephone')
            {
                array_push($resultat, $contact->getTelephone());
            }
        }
        return $resultat;
    }
   public function getMails()
    {
        $resultat = array();
        $contacts = $this->parent->getContacts();
        
        foreach($contacts as $contact)
        {
            if($contact->getType() == 'mail')
            {
                array_push($resultat, $contact->getMail());
            }
        }
        return $resultat;
    }
}