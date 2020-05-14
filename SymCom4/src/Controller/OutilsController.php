<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @version 1.1.0 // 03/04/2020 - 16:17
 * @author Jérôme CARION <jerome.carion@gmail.com>
 */
abstract class OutilsController extends AbstractController
{
    protected $twig;
    protected $paramTwig = array();
    protected $redirect;
    protected $paramRedirect = array();
    protected $pageService = null;
    protected $idPageActuelle = null;

    
    /**
     * Permet de supprimer un objet de la base avec son ID
     *
     * @param [ObjectRepository] $repo
     * @param [EntityManagerInterface] $manager
     * @param [integer] $id
     * @return void
     */
    protected function delete($repo, $manager, $id):void
    {
        //On récupére l'objet
        $objet = $repo->findOneById($id);
        //On supprime l'objet
        $manager->remove($objet);
        //On enregistre dans la BDD
        $manager->flush();
    }

    /******************************************************************************************** */
    /**
     * Permet de définir le Twig qui sera utilisé lors de l'affichage
     *
     * @param [string] $twig //Chemin du twig
     * @return void
     */
    protected function defineTwig($twig)
    {
        $this->twig = $twig;
    }

    /**
     * Permet de définit les paramètres utiles au Twig lors de l'affichage
     *
     * @param [string] $cle //Nom de la variable dans le twig
     * @param [type] $valeur //Données qui seront utilisées dans le twig
     * @return void
     */
    protected function defineParamTwig($cle, $valeur)
    {
        $this->paramTwig[$cle] = $valeur;
    }

    /******************************************************************************************** */
    /**
     * Permet de définir le Redirect qui sera utilisé lors de l'affichage
     *
     * @param [string] $redirect //Nom du chemin
     * @return void
     */
    protected function defineRedirect($redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * Permet de définit les paramètres utiles au Redirect lors de l'affichage
     *
     * @param [type] $valeur //Données qui seront utilisées dans le redirect
     * @return void
     */
    protected function defineParamRedirect($valeur)
    {
        $this->paramRedirect = $valeur;
    }

    /** ==================================================================== */
    /** GESTION DU FORMULAIRE SERVICE */

    /** Permet de générer un form dans un controller pour le FormulaireService */
    protected function createFormService()
    {
        $this->formulaireService->setForm($this->createForm($this->formulaireService->getClassType(), $this->formulaireService->getElement()));
        $this->formulaireService->creerFormulaire();
    }

    /** Permet de récupérer les informations de la page mère pour les fournir au Formulaire Service */
    protected function addPageMereFormService()
    {
        $pageMere = $this->pageService->getPageMere();
        $this->formulaireService->setPageResultat($pageMere->getNomChemin());
        $this->formulaireService->setPageResultatConfig($pageMere->getParams());
    }        

    /** ==================================================================== */
    /** UTILISATION SUR L'ENSEMBLE DES PAGES */

    /**
     * Affiche la page requise
     *
     * @return Response
     */
    protected function Afficher():Response
    {
        //Récupérer l'id de la page en cours
        if($this->idPageActuelle == null)
        {
            $this->idPageActuelle = $this->pageService->getPageId();
        }

        //Passer l'id de la page mere à notre GestionService
        if($this->pageService->getPageMere() != null)
        {
            $this->gestionService->setIdPageMere($this->pageService->getPageMere()->getId());
        }
        $this->gestionService->setIdPageActuelle($this->idPageActuelle);

        //Vérifier si twig est vide et qu'il n'y a pas encore de redirect, alors c'est que nous devons attendre une réponse de formulaireService
        if($this->twig == null && $this->redirect == null)
        {
            //Vérifier si le formulaireService souhaite effectuer une redirection ou appeler un twig
            if($this->formulaireService->getRedirect() != null)
            {
                $this->defineRedirect($this->formulaireService->getRedirect());
                $this->defineParamRedirect($this->formulaireService->getPageResultatConfig());
            }
            else
            {
                $this->defineTwig($this->formulaireService->getTwigFormulaire());
                $this->defineParamTwig('form', $this->formulaireService->getForm());
                $this->defineParamTwig('element', $this->formulaireService->getElement());
            }   

            //Vérifier si le formulaireService souhaite faire passer des messages flush
            foreach($this->formulaireService->getMessagesFlash() as $message)
            {
                $this->addFlash($message[0], $message[1]);
            } 
        }
        
        //Donner le gestionService à Twig
        $this->defineParamTwig('gestionService', $this->gestionService);

        //Enregistrer la page
        if($this->pageService != null)
        {
            $this->pageService->Enregistrer();
        }

        //Vérifier si redirection ou affichage
        if($this->redirect != null)
        {
            //Afficher la redirection
            return $this->redirectToRoute($this->redirect, $this->paramRedirect);
        }
        else
        {
            //Affiche la page
            return $this->render($this->twig, $this->paramTwig);
        }
    }
}