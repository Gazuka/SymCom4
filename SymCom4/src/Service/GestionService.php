<?php

namespace App\Service;

use Twig\Environment;
use App\Service\ContactService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class GestionService {
    private $twig; //Objet permettant l'affichage de Twig directement dans du html
    private $idPage; //L'id de la page de base pour eventuellement y revenir par la suite
    private $contactService; //Utilisé pour récupérer des contacts par type
    private $parent; //Objet général sur lequel on effectu des gestions
    
    /************************************************************************************/
    public function __construct(Environment $twig, RequestStack $request, ContactService $contactService)
    {
        $this->twig = $twig;
        $this->routeActuelle = $request->getCurrentRequest()->attributes->get('_route');
        $this->contactService = $contactService;
    }
    public function setIdPage($id)
    {
        $this->idPage = $id;
    }
    public function setContactService($contactService)
    {
        $this->contactService = $contactService;
    }
    public function setParent($parent)
    {
        $this->parent = $parent;
    }
    /************************************************************************************/
    /** Affiche dans TWIG un tableau pour voir les adresses, les modifier ou en ajouter
     *
     * @return void
     */
    public function gestionAdresses()
    {
        $this->twig->display('symcom4/admin/gestion/_gestion_adresses.html.twig',
        [
            'parent' => $this->parent,
            'adresses' => $this->contactService->getAdresses($this->parent),
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
            'mails' => $this->contactService->getMails($this->parent),
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
            'telephones' => $this->contactService->getTelephones($this->parent),
            'idPage' => $this->idPage
        ]); 
    }
    /** Affiche dans TWIG un tableau pour voir les liens, les modifier ou en ajouter
     *
     * @return void
     */
    public function gestionLien()
    {
        $this->twig->display('symcom4/admin/gestion/_gestion_lien.html.twig',
        [
            'parent' => $this->parent,
            'lien' => $this->parent->getLien(),
            'idPage' => $this->idPage
        ]); 
    }
    /** Affiche dans TWIG les bases d'un service (nom, presentation, local) et le bouton de modification
     *
     * @return void
     */
    public function gestionServiceBase()
    {
        $this->twig->display('symcom4/admin/gestion/_gestion_service_base.html.twig',
        [
            'structure' => $this->parent,
            'idPage' => $this->idPage
        ]); 
    }
}