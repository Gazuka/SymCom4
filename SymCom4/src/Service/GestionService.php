<?php

namespace App\Service;

use Twig\Environment;
use App\Service\ContactService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class GestionService {
    private $twig; //Objet permettant l'affichage de Twig directement dans du html
    private $idPageMere; //L'id de la page de base pour eventuellement y revenir par la suite
    private $idPageActuelle; //L'id de la page actuelle pour eventuellement y revenir par la suite
    private $contactService; //Utilisé pour récupérer des contacts par type

    private $request;
    
    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** FONCTIONS MAGIQUES ********************************************************************/

    public function __construct(Environment $twig, RequestStack $request, ContactService $contactService)
    {
        $this->twig = $twig;
        $this->routeActuelle = $request->getCurrentRequest()->attributes->get('_route');

        $this->contactService = $contactService;
        $this->request = $request;
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** FONCTIONS GET ET SET ******************************************************************/

    public function setIdPageMere($id)
    {
        $this->idPageMere = $id;
    }

    public function setIdPageActuelle($id)
    {
        $this->idPageActuelle = $id;
    }

    public function setContactService($contactService)
    {
        $this->contactService = $contactService;
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** FONCTIONS PUBLIQUES *******************************************************************/

    public function gestionContacts($parent)
    {
        $this->twig->display('symcom4/admin/gestion/_gestion_contacts.html.twig',
        [
            'parent' => $parent,
            'gestionService' => $this
        ]); 
    }

    /************************************************************************************/
    /** Affiche dans TWIG un tableau pour voir les adresses, les modifier ou en ajouter
     *
     * @return void
     */
    public function gestionAdresses($parent)
    {
        $this->twig->display('symcom4/admin/gestion/_gestion_structure_adresses.html.twig',
        [
            'parent' => $parent,
            'adresses' => $this->contactService->getAdresses($parent),
            'idPage' => $this->idPageActuelle
        ]); 
    }

    /** Affiche dans TWIG un tableau pour voir les e-mails, les modifier ou en ajouter
     *
     * @return void
     */
    public function gestionMails($parent)
    {
        // Permet de choisir la bonne page twig en fonction du parent
        switch(substr(strrchr(get_class($parent), "\\"), 1))
        {
            case 'Structure':
                $fichierTwig = 'symcom4/admin/gestion/_gestion_structure_mails.html.twig';
            break;
            case 'Fonction':
                $fichierTwig = 'symcom4/admin/gestion/_gestion_fonction_mails.html.twig';
            break;
            case 'Humain':
                $fichierTwig = 'symcom4/admin/gestion/_gestion_humain_mails.html.twig';
            break;
        }
        $this->twig->display($fichierTwig,
        [
            'parent' => $parent,
            'mails' => $this->contactService->getMails($parent),
            'idPage' => $this->idPageActuelle
        ]); 
    }
    /** Affiche dans TWIG un tableau pour voir les téléphones, les modifier ou en ajouter
     *
     * @return void
     */

    public function gestionTelephones($parent)
    {
        // Permet de choisir la bonne page twig en fonction du parent
        switch(substr(strrchr(get_class($parent), "\\"), 1))
        {
            case 'Structure':
                $fichierTwig = 'symcom4/admin/gestion/_gestion_structure_telephones.html.twig';
            break;
            case 'Fonction':
                $fichierTwig = 'symcom4/admin/gestion/_gestion_fonction_telephones.html.twig';
            break;
            case 'Humain':
                $fichierTwig = 'symcom4/admin/gestion/_gestion_humain_telephones.html.twig';
            break;
        }
        $this->twig->display($fichierTwig,
        [
            'parent' => $parent,
            'telephones' => $this->contactService->getTelephones($parent),
            'idPage' => $this->idPageActuelle
        ]); 
    }

    /** Affiche dans TWIG un tableau pour voir les liens, les modifier ou en ajouter
     *
     * @return void
     */
    public function gestionLien($parent)
    {
        $this->twig->display('symcom4/admin/gestion/_gestion_lien.html.twig',
        [
            'parent' => $parent,
            'lien' => $parent->getLien(),
            'idPage' => $this->idPageActuelle
        ]); 
    }

    /** Affiche dans TWIG un tableau pour voir les images, les modifier ou en ajouter
     *
     * @return void
     */
    public function gestionImage($parent)
    {
        $this->twig->display('symcom4/admin/gestion/_gestion_image.html.twig',
        [
            'parent' => $parent,
            'image' => $parent->getImage(),
            'idPage' => $this->idPageActuelle
        ]); 
    }

    /** Affiche dans TWIG les bases d'un service (nom, presentation, local) et le bouton de modification
     *
     * @return void
     */
    public function gestionServiceBase($parent)
    {
        $this->twig->display('symcom4/admin/gestion/_gestion_service_base.html.twig',
        [
            'structure' => $parent,
            'idPage' => $this->idPageActuelle
        ]); 
    }

    /** Affiche dans TWIG les bases d'une association (nom, presentation, local, type et sigle) et le bouton de modification
     *
     * @return void
     */
    public function gestionAssociationBase($parent)
    {
        $this->twig->display('symcom4/admin/gestion/_gestion_association_base.html.twig',
        [
            'structure' => $parent,
            'idPage' => $this->idPageActuelle
        ]); 
    }

    /** Affiche dans TWIG les types d'une association et le bouton de modification
     *
     * @return void
     */
    public function gestionAssociationType($parent)
    {
        $this->twig->display('symcom4/admin/gestion/_gestion_association_type.html.twig',
        [
            'structure' => $parent,
            'idPage' => $this->idPageActuelle
        ]); 
    }

    /** Affiche dans TWIG les bases d'une entreprise (nom, presentation, local, type et sigle) et le bouton de modification
     *
     * @return void
     */
    public function gestionEntrepriseBase($parent)
    {
        $this->twig->display('symcom4/admin/gestion/_gestion_entreprise_base.html.twig',
        [
            'structure' => $parent,
            'idPage' => $this->idPageActuelle
        ]); 
    }

    /** Affiche dans TWIG les types d'une entreprise et le bouton de modification
     *
     * @return void
     */
    public function gestionEntrepriseType($parent)
    {
        $this->twig->display('symcom4/admin/gestion/_gestion_entreprise_type.html.twig',
        [
            'structure' => $parent,
            'idPage' => $this->idPageActuelle
        ]); 
    }
    
    /** Affiche dans TWIG un tableau pour voir les adresses, les modifier ou en ajouter
     *
     * @return void
     */
    public function gestionStructureFonctions($parent)
    {
        $this->twig->display('symcom4/admin/gestion/_gestion_structure_fonctions.html.twig',
        [
            'parent' => $parent,
            'fonctions' => $parent->getFonctions(),
            'idPage' => $this->idPageActuelle,
            'gestionService' => $this
        ]); 
    }
}