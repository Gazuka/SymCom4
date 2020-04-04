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

    /** Création d'un formulaire pour un nouveau element (objet entity)
     * 
     * @param mixed $variables // ce paramètre doit être un tableau de type clé => valeur avec toutes les clés obligatoires :
     *                              ['element'] => Objet que l'on souhaite obtenir avec ce formulaire
     *                              ['request'] => Objet Request
     *                              ['manager'] => Objet ObjectManager
     *                              ['classType'] => Classe du formulaire (ObjetType::class)
     *                              ['pagedebase'] => Chemin du template du formulaire
     *                              ['pagederesultat'] => Nom de la page de redirection après validation du formulaire
     *                              ['texteConfirmation'] => Texte affiché lors de la validation du formulaire
     * @param mixed $options // ce paramètre doit être un tableau de type clé => valeur avec des clés qui sont toutes facultatives :
     *                              ['pagederesultatConfig']
     *                              ['dependances'] => Le tableau de ses dépendances sous la forme ['Dependances' => 'Element'] (les noms de dépendances prennent un "s", l'élément reste au singulier !)
     *                              ['texteConfirmationEval'] => Permet de dynamiser le 'texteConfirmation' en remplaçant des sections du code (ex : $variables['texteConfirmationEval']["###"] = '$element->getNom();';)
     *                              ['deletes'] => Le tableau des objets susceptible de devenir orphelin sous la forme ['findBy' => 'element', 'classEnfant' => 'sousElement', 'repo' => $repoSousElement] (element : nom de l'élément actif dans la BDD, sousElement : nom de la sous classe au pluriel, repoSousElement : repository de la sous classe)
     *                              ['actions'] => Le tableau de fonction à executer si le formulaire est valide ['name' => 'nomdelafonction', 'params' => 'tableaudesparams']
     */
    protected function afficherFormulaire(array $variables, array $options = array()):void{
        //Permet d'extraire les variables reçues avec le paramètre $variables 
        extract($variables);        
        extract($options);
        //On crée le formulaire pour l'élèment de la classe
        $form = $this->createForm($classType, $element);        
        $form->handleRequest($request);

        //On vérifie que le formulaire soit soumis et valide
        if($form->isSubmitted() && $form->isValid()) 
        {
            //On effectue les actions si besoins pour modifier l'element
            if(isset($actions))
            {
                $element = $this->formulaire_Actions($element, $actions, $request);
            }

            //On persist l'élément
            $manager->persist($element);

            //On persist ses dependances
            if(isset($dependances))
            {
                $manager = $this->formulaire_Dependances($element, $dependances, $manager);
            }

            //On delete ses dependances orphelines...
            if(isset($deletes))
            {
                $manager = $this->formulaire_Deletes($element, $deletes, $manager);
            }

            //On enregistre le tout
            $manager->flush();

            //On dynamise le texte de confirmation du formulaire
            if(isset($texteConfirmationEval))
            {
                $texteConfirmation = $this->formulaire_DynamiseTexte($texteConfirmation, $texteConfirmationEval, $element);
            }
            
            //On affiche un message de validation
            $this->addFlash(
                'success',
                $texteConfirmation
            );

            //On affiche la page de résultat
            if(!isset($pagederesultatConfig))
            {
                $pagederesultatConfig = array();
            }
            //On prépare la redirection
            $this->defineRedirect($pagederesultat);
            $this->defineParamRedirect($pagederesultatConfig);
        }
        //On défini les données à envoyer à Twig
        $this->defineTwig($pagedebase);
        $this->defineParamTwig('form', $form->createView());
        $this->defineParamTwig('element', $element);
    }


    /* ====================================================================================================== */


    /** Permet de modifier les dépendances d'un élement d'une classe quelconque
     *
     * @param Object $element //L'objet modifié initialement
     * @param array $dependances //Le tableau de ses dépendances sous la forme ['Dependances' => 'Element'] (les noms de dépendances prennent un "s", l'élément reste au singulier !)
     * @param ObjectManager $manager //L'objet Manager afin de retourner les différents "persist" qui seront "flush" en même temps que le reste dans la fonction initiale
     * @return ObjectManager
     */
    private function formulaire_Dependances(Object $element, array $dependances, ObjectManager $manager):ObjectManager{
        foreach($dependances as $dependance => $elem)
        {
            //On récupére les objets du type dépendance qui se raccroche à notre element
            eval('$objets = $element->get'.$dependance.'();');
            //Pour chacun des objets dépendant, on ajoute notre élement
            foreach($objets as $objet)
            {
                //Il faut utiliser addElement pour les relation ManyToMany et SetElement pour le reste, si la fonction addElement existe on l'utilise...
                if(method_exists($objet, 'add'.$elem))
                {
                    eval('$objet->add'.$elem.'($element);'); 
                }
                else
                {
                    eval('$objet->set'.$elem.'($element);'); 
                }
                $manager->persist($objet);
            }
        }
        //On ne "flush" rien ici, mais nous retournons le manager afin que les "persist" soit traité plus tard.
        return $manager;
    }


    /* ====================================================================================================== */


    /** Permet de supprimer les objets orphelins ayant un lien avec notre élément
     *
     * @param Object $element //L'objet modifié initialement
     * @param array $deletes //Le tableau des objets susceptible de devenir orphelin sous la forme ['findBy' => 'element', 'classEnfant' => 'sousElement', 'repo' => $repoSousElement] (element : nom de l'élément actif dans la BDD, sousElement : nom de la sous classe au pluriel, repoSousElement : repository de la sous classe)
     * @param ObjectManager $manager //L'objet Manager afin de retourner les différents "persist" qui seront "flush" en même temps que le reste dans la fonction initiale
     * @return ObjectManager
     */
    private function formulaire_Deletes(Object $element, array $deletes, ObjectManager $manager):ObjectManager{
        foreach($deletes as $delete)
        {
            //On récupére ici les variables : $findBy, $classEnfant, $repo
            extract($delete); 
            //Récupére tous les sousElement de notre Element
            $recup = $repo->findBy([$findBy => $element]);
            //Pour chaque sousElement, on vérifie si il doit être supprimer ou pas
            foreach($recup as $elem)
            {
                eval('$elems = $element->get'.$classEnfant.'();');
                if(!$elems->contains($elem))
                {
                    $manager->remove($elem);
                }
                //$manager->persist();//$manager->persist($objet);
            }
        }
        //On ne "flush" rien ici, mais nous retournons le manager afin que les "persist" soit traité plus tard.
        return $manager;
    }

    
    /* ====================================================================================================== */

    /** Permet d'effectuer des actions lors de la validation du formulaire afin de modifier l'element
     *
     * @param Object $element //L'objet modifié initialement
     * @param array $actions //Le tableau de fonction à executer si le formulaire est valide ['name' => 'nomdelafonction', 'params' => 'tableaudesparams']
     * @return Object
     */
    private function formulaire_Actions(Object $element, array $actions, $request):Object{
        foreach($actions as $action)
        {
            //On récupére ici les variables : $name, $params
            extract($action); 
            //On lance la fonction qui retourne le manager modifié
            $element = $this->$name($element, $params, $request);
        }
        //On retourne l'element modifié.
        return $element;
    }

    
    /* ====================================================================================================== */

    /** Permet de dynamiser des emplacements spécifiques d'un message
     *
     * @param string $texte //Le texte de base
     * @param array $optimisation //Un tableau de modification sous la forme ['emplacement' => 'methode'] (emplacement : ### par exemple, methode : $element->getNom();)
     * @param Object $element //L'objet modifié initialement
     * @return string
     */
    private function formulaire_DynamiseTexte(string $texte, array $optimisation, Object $element): string
    {
        foreach($optimisation as $key => $valeur)
        {
            eval('$valeur = '.$valeur);
            $texte = str_replace($key, $valeur, $texte);
        }
        return $texte;
    }


    /* ====================================================================================================== */


    

/* ====================================================================================================== */


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

    /**
     * Affiche la page requise
     *
     * @return Response
     */
    protected function Afficher():Response
    {
        if($this->redirect != null)
        {
            return $this->redirectToRoute($this->redirect, $this->paramRedirect);
        }
        //Affiche la page
        return $this->render($this->twig, $this->paramTwig);
    }
}