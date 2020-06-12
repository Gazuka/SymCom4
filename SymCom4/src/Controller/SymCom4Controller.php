<?php

namespace App\Controller;


use App\Entity\Page;
use Gazuka\Outils\Outils;
use App\Service\PageService;
use App\Service\ContactService;
use App\Service\GestionService;
use App\Controller\OutilsController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

// !!! Supprimer le extends OutilsController lorsque Gazuka/Outils sera totalement intégré au site
class SymCom4Controller extends OutilsController
{
    //Instance de Gazuka/Outils
    protected $outilsBox;
    
    //protected $idPageActuelle;

    protected $contactService;
    protected $gestionService;
    protected $liensRapides = array();
    protected $request;    

    public function __construct(EntityManagerInterface $manager, RequestStack $requestStack, PageService $pageService, GestionService $gestionService, ContactService $contactService)
    {
        //Création d'une instance de Gazuka/Outils
        $this->outilsBox = new Outils($manager, $requestStack, Page::class);

        $this->pageService = $pageService;
        $this->gestionService = $gestionService;
        $this->contactService = $contactService;
        
    }

    protected function jobControllerCreateByGazuka()
    {
        //Si besoin on crée un formulaire
        if($this->outilsBox->getFormClassType() != null && $this->outilsBox->getFormElement() != null)
        {
            $this->outilsBox->setFormForm($this->createForm($this->outilsBox->getFormClassType(), $this->outilsBox->getFormElement()));
            $this->outilsBox->creerFormulaire($this);
        }
        
        //On récupére le jobController
        $jobController = $this->outilsBox->recupJobController();

        //On récupére les messages flush
        //???????????????????????????????????????????????????? A faire

        //On enregistre les données du manager
        //$this->outilsBox->enregistrer(); Directement dans outils

        //On recherche ce qui doit être affiché
        switch($jobController['affichage']['fonction'])
        {
            case 'redirectToRoute':
                return $this->redirectToRoute($jobController['affichage']['route'], $jobController['affichage']['params']);
            break;
            case 'render':
                return $this->render($jobController['affichage']['twig'], $jobController['affichage']['params']);
            break;
            default:
                //Affichage d'une page d'erreur ou d'une redirection !
                dd("Penser à mettre une page d'erreur !");
            break;
        }
    }

    protected function jobController()
    {
        //Passer l'id de la page mere et de la page actuelle à notre GestionService
        if($this->outilsBox->getPagePageMere() != null)
        {
            $this->gestionService->setIdPageMere($this->outilsBox->getPagePageMere()->getId());
        }
        $this->gestionService->setIdPageActuelle($this->outilsBox->getPageIdActuel());

        //Donner le gestionService à Twig
        $this->outilsBox->addParamTwig('gestionService', $this->gestionService);
        
        //Enregistrer la page
        // if($this->pageService != null)
        // {
        //     $this->pageService->Enregistrer();
        // }

        // Demande à Gazuka/outils de faire son Job !
        return $this->jobControllerCreateByGazuka();
    }
}