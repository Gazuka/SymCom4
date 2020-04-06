<?php

namespace App\Controller;

use App\Entity\Lien;
use App\Entity\Page;
use App\Controller\OutilsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SymCom4Controller extends OutilsController
{
    protected $liensRapides = array();

    /**
     * @Route("/", name="symcom4")
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
            case 'services':
                $this->defineParamTwig('nav_titre', 'Les services');
                //Création d'un lien vers la page Nouveau service
                $this->addLienRapide('admin_service_new', 'Ajouter un service', 'success', 'fas fa-plus');
            break;
            case 'associations':
                $this->defineParamTwig('nav_titre', 'Les associations');
                //Création d'un lien vers la page Nouvelle association
                $this->addLienRapide('admin_association_new', 'Ajouter une association', 'success', 'fas fa-plus');
                //Création d'un lien vers la page Nouveau type d'association
                $this->addLienRapide('admin_associations_type_new', "Ajouter un type d'association", 'success', 'fas fa-plus');
            break;
            case 'medias':
                $this->defineParamTwig('nav_titre', 'Médias');
                //Création d'un lien vers la page Nouveau dossier
                $this->addLienRapide('admin_medias_dossier_new', 'Ajouter un dossier', 'success', 'fas fa-plus');
                $this->addLienRapide('admin_medias_new', 'Ajouter un média', 'success', 'fas fa-plus');
            break;
            case 'humains':
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
