<?php

namespace App\Controller;

use App\Entity\Lien;
use App\Entity\Mail;
use App\Entity\Page;
use App\Entity\Adresse;
use App\Entity\Telephone;
use App\Form\NewMailType;
use App\Form\NewAdresseType;
use App\Service\PageService;
use App\Form\NewTelephoneType;
use App\Service\OutilsService;
use App\Service\GestionService;
use App\Service\FormulaireService;
use App\Controller\OutilsController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SymCom4Controller extends OutilsController
{
    protected $formulaireService;
    protected $gestionService;
    protected $outilsService;

    protected $liensRapides = array();
    protected $request;    

    public function __construct(PageService $pageService, FormulaireService $formulaireService, GestionService $gestionService, OutilsService $outilsService)
    {
        $this->pageService = $pageService;
        $this->formulaireService = $formulaireService;
        $this->gestionService = $gestionService;
        $this->outilsService = $outilsService;
    }

    /**
     * @Route("/", name="accueil")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index()
    {
        return $this->render('symcom4/public/index.html.twig', [
            'controller_name' => 'SymCom4Controller',
        ]);
    }

    /**********************************************************************/
    /**************** FONCTIONS UTILES ************************************/
    /**********************************************************************/
    /**
     * Permet de définir les données obligatoire dans Twig
     *
     * @param [string] $categ
     * @return void
     */
    protected function initTwig($categ = null): void
    {
        //Prépare les données suivant la categorie
        switch($categ)
        {
            case 'service':
                $this->defineParamTwig('nav_titre', 'Les services');
                //Création d'un lien vers la page Nouveau service
                $this->addLienRapide('admin_structures_service_new', 'Ajouter un service', 'success', 'fas fa-plus');
            break;
            case 'association':
                $this->defineParamTwig('nav_titre', 'Les associations');
                //Création d'un lien vers la page Nouvelle association
                $this->addLienRapide('admin_structures_association_new', 'Ajouter une association', 'success', 'fas fa-plus');
                //Création d'un lien vers la page Nouveau type d'association
                $this->addLienRapide('admin_structures_associations_type_new', "Ajouter un type d'association", 'success', 'fas fa-plus');
            break;
            case 'entreprise':
                $this->defineParamTwig('nav_titre', 'Les entreprises');
                //Création d'un lien vers la page Nouvelle association
                $this->addLienRapide('admin_structures_entreprise_new', 'Ajouter une entreprise', 'success', 'fas fa-plus');
                //Création d'un lien vers la page Nouveau type d'association
                $this->addLienRapide('admin_structures_entreprises_type_new', "Ajouter un type d'entreprise", 'success', 'fas fa-plus');
            break;
            case 'fonction':
                $this->defineParamTwig('nav_titre', 'Les fonctions');
                //Création d'un lien vers la page Nouvelle fonction
                //$this->addLienRapide('admin_fonction_new', 'Ajouter une fonction', 'success', 'fas fa-plus');
                //Création d'un lien vers la page nouveau type de fonction
                $this->addLienRapide('admin_fonctions_type_new', "Ajouter un type de fonction", 'success', 'fas fa-plus');
            break;
            case 'medias':
                $this->defineParamTwig('nav_titre', 'Médias');
                //Création d'un lien vers la page Nouveau dossier
                $this->addLienRapide('admin_medias_dossier_new', 'Ajouter un dossier', 'success', 'fas fa-plus');
                $this->addLienRapide('admin_medias_new', 'Ajouter un média', 'success', 'fas fa-plus');
            break;
            case 'humain':
                $this->defineParamTwig('nav_titre', 'Personnes');
                //Création d'un lien vers la page Nouveau dossier
                $this->addLienRapide('admin_humain_new', 'Ajouter une personne', 'success', 'fas fa-plus');
            break;
            default:
                $this->defineParamTwig('nav_titre', '');
            break;
        }
        //Transfert des liens rapides à Twig
        $this->defineParamTwig('liensRapides', $this->liensRapides);
    }

    /**
     * Ajoute un lien rapide pour utilisation dans Twig
     *
     * @param [string] $nomChemin
     * @param [string] $label
     * @param [string] $colorBoot
     * @param [string] $icon
     * @return void
     */
    protected function addLienRapide($nomChemin, $label, $colorBoot, $icon): void
    {
        //Création d'un lien vers la page Nouveau service
        $page = new Page();
        $page->setNomChemin($nomChemin);
        $page->setUrl($this->generateUrl($page->getNomChemin()));
        $lien = new Lien();
        $lien->setLabel($label);
        $lien->setColorBoot($colorBoot);
        $lien->setFontAwesome($icon);
        $lien->setPage($page);
        array_push($this->liensRapides, $lien);
    }
}
