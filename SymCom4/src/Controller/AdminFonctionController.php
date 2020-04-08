<?php

namespace App\Controller;

use App\Entity\Fonction;
use App\Entity\TypeFonction;
use App\Form\NewFonctionType;
use App\Form\NewTypeFonctionType;
use App\Repository\FonctionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeFonctionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminFonctionController extends SymCom4Controller
{
    /******************************************************************************************/
    /****************************************Les fonctions ************************************/
    /**
     * @Route("/admin/fonctions", name="admin_fonctions")
     */
    public function fonctions(FonctionRepository $repo): Response
    {
        //Récupére toutes les fonctions
        $fonctions = $repo->findAll();
        //Prépare le Twig
        $this->initTwig('fonctions');
        $this->defineTwig('symcom4/admin/fonctions/fonctions.html.twig');
        $this->defineParamTwig('fonctions', $fonctions);
        //Affiche la page
        return $this->Afficher();
    }
    /**
     * @Route("/admin/fonction/new", name="admin_fonction_new")
     */
    public function newfonction(Request $request, EntityManagerInterface $manager):Response
    {
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Fonction();
        $variables['classType'] = NewFonctionType::class;
        $variables['pagedebase'] = 'symcom4/admin/fonctions/new_fonction.html.twig';
        $variables['pagederesultat'] = 'admin_structures_associations';
        $variables['texteConfirmation'] = "La fonction ### a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getTitre();'];
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('fonctions');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/fonction/edit/{id}", name="admin_fonction_edit")
     */
    public function editFonction(FonctionRepository $repo, Request $request, EntityManagerInterface $manager, $id):Response
    {
        //On recupére le Service
        $fonction = $repo->findOneById($id);
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $fonction;
        $variables['classType'] = NewFonctionType::class;
        $variables['pagedebase'] = 'symcom4/admin/fonctions/new_fonction.html.twig';
        $variables['pagederesultat'] = 'admin_fonctions';
        $variables['texteConfirmation'] = "La fonction ### a bien été modifiée !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getTitre();'];
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('fonctions');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/fonction/delete/{id}", name="admin_fonction_delete")
     */
    public function deleteFonction(FonctionRepository $repo, EntityManagerInterface $manager, $id):Response
    {
        //On supprimer le Service
        $this->delete($repo, $manager, $id);
        //Défini la page de redirection
        $this->defineRedirect('admin_fonctions');
        //Prépare le Twig
        $this->initTwig('fonctions');
        //Affiche la redirection
        return $this->Afficher();
    }
    /******************************************************************************************/
    /****************************************Les types de fonctions *************************/
    /**
     * @Route("/admin/fonctions/types", name="admin_fonctions_types")
     */
    public function typeFonctions(TypeFonctionRepository $repo): Response
    {
        //Récupére tous les types d'associations
        $typefonctions = $repo->findAll();
        //Prépare le Twig
        $this->initTwig('fonctions');
        $this->defineTwig('symcom4/admin/fonctions/types/typefonctions.html.twig');
        $this->defineParamTwig('typefonctions', $typefonctions);
        //Affiche la page
        return $this->Afficher();
    }
    /**
     * @Route("/admin/fonctions/type/new", name="admin_fonctions_type_new")
     */
    public function newTypeFonction(Request $request, EntityManagerInterface $manager):Response
    {
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new TypeFonction();
        $variables['classType'] = NewTypeFonctionType::class;
        $variables['pagedebase'] = 'symcom4/admin/fonctions/types/new_typefonction.html.twig';
        $variables['pagederesultat'] = 'admin_fonctions_types';
        $variables['texteConfirmation'] = "Le type de fonction ### a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getTitre();'];
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('fonctions');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/fonctions/type/edit/{id}", name="admin_fonctions_type_edit")
     */
    public function editTypeFonction(TypeFonctionRepository $repo, Request $request, EntityManagerInterface $manager, $id):Response
    {
        //On recupére le type d'association
        $typefonction = $repo->findOneById($id);
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $typefonction;
        $variables['classType'] = NewTypeFonctionType::class;
        $variables['pagedebase'] = 'symcom4/admin/fonctions/types/new_typefonction.html.twig';
        $variables['pagederesultat'] = 'admin_fonctions_types';
        $variables['texteConfirmation'] = "Le type de fonction ### a bien été modifié !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getTitre();'];
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('fonctions');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/fonctions/type/delete/{id}", name="admin_fonctions_type_delete")
     */
    public function deleteTypeFonction(TypeFonctionRepository $repo, EntityManagerInterface $manager, $id):Response
    {
        //On supprimer le Service
        $this->delete($repo, $manager, $id);
        //Défini la page de redirection
        $this->defineRedirect('admin_fonctions');
        //Prépare le Twig
        $this->initTwig('fonctions');
        //Affiche la redirection
        return $this->Afficher();
    }
}
