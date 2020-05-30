<?php

namespace App\Controller;

use App\Entity\Lien;
use App\Entity\Page;
use App\Entity\Service;
use App\Entity\Association;
use App\Form\NewServiceType;
use App\Entity\TypeAssociation;
use App\Form\NewAssociationType;
use App\Form\NewTypeAssociationType;
use App\Controller\SymCom4Controller;
use App\Repository\DossierRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AssociationRepository;
use App\Repository\TypeAssociationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends SymCom4Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        //Prépare le Twig
        $this->defineTwig('symcom4/admin/index.html.twig');
        $this->initTwig();
        //Affiche la page
        return $this->Afficher();
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
