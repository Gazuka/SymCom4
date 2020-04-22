<?php

namespace App\Controller;

use App\Entity\Mail;
use App\Entity\Humain;
use App\Entity\Adresse;
use App\Entity\Contact;
use App\Entity\Telephone;
use App\Form\NewMailType;
use App\Entity\Utilisateur;
use App\Form\NewHumainType;
use App\Form\NewAdresseType;
use App\Service\PageService;
use App\Form\UtilisateurType;
use App\Form\NewTelephoneType;
use App\Controller\AdminController;
use App\Form\HumainUtilisateurType;
use App\Repository\HumainRepository;
use App\Repository\ContactRepository;
use App\Repository\StructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminContactController extends AdminController
{
    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** HUMAINS - VOIR, AJOUTER, MODIFIER, SUPPRIMER ******************************************/

    /**
     * Afficher tous les humains
     * 
     * @Route("/admin/humains", name="admin_humains")
     *
     * @return Response
     */
    public function humains():Response
    {
        //Récupérer tous les humains
        $humains = $this->outilsService->findAll(Humain::class);

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('humain');

        //Définir le twig à afficher
        $this->defineTwig('symcom4/admin/humains/humains.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('humains', $humains);

        //Afficher la page
        return $this->Afficher();
    }

    /**
     * Créer un humain
     * 
     * @Route("/admin/humain/new", name="admin_humain_new")
     *
     * @return Response
     */
    public function newHumain():Response
    {
        //Gérer le formulaire
        $this->formulaireService->setElement(new Humain());
        $this->formulaireService->setClassType(NewHumainType::class);
        $this->formulaireService->setTwigFormulaire('symcom4/admin/humains/new_humain.html.twig');
        $this->formulaireService->setPageResultat('admin_humains');
        $this->formulaireService->setTexteConfirmation("### a bien été créé !");
        $this->formulaireService->setTexteConfirmationEval(["###" => '$this->element->getNom()." ".$this->element->getPrenom();']);
        $this->createFormService();
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('humain');
        
        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Modifier un humain
     * 
     * @Route("/admin/humain/edit/{idhumain}", name="admin_humain_edit")
     *
     * @param integer $idhumain
     * @return Response
     */
    public function editHumain(int $idhumain):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idhumain'));

        //Récupérer l'objet Humain
        $humain = $this->outilsService->findById(Humain::class, $idhumain);

        //Gérer le formulaire
        $this->formulaireService->setElement($humain);
        $this->formulaireService->setClassType(NewHumainType::class);
        $this->formulaireService->setTwigFormulaire('symcom4/admin/humains/new_humain.html.twig');
        $this->formulaireService->setPageResultat('admin_humains');
        $this->formulaireService->setTexteConfirmation("### a bien été modifié !");
        $this->formulaireService->setTexteConfirmationEval(["###" => '$this->element->getNom()." ".$this->element->getPrenom();']);
        $this->createFormService();

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('humain');

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Supprimer un humain
     * 
     * @Route("/admin/humain/delete/{idhumain}", name="admin_humain_delete")
     *
     * @param integer $idhumain
     * @return Response
     */
    public function deleteHumain(int $idhumain):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idhumain'));

        //Supprimer le contact de la BDD
        $this->outilsService->delete(Humain::class, $idhumain);
        
        //Afficher un message de validation
        $this->addFlash('success', 'Cette personne a bien été supprimée !');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('humain');

        //Définir la page de redirection
        $this->defineRedirect('admin_humains');

        //Afficher la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** HUMAINS - AJOUTER un CONTACT **********************************************************/

    /**
     * Ajouter un contact à un humain
     * 
     * @Route("/admin/humain/addcontact/{idhumain}/{type}", name="admin_humain_addcontact")
     *
     * @param integer $idhumain
     * @param integer $idpage
     * @param string $type
     * @return Response
     */
    public function addcontactHumain(int $idhumain, int $idpage, string $type):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idhumain', 'type'));

        //Récupérer l'objet Humain
        $humain = $this->outilsService->findById(Humain::class, $idhumain);

        //Gérer le formulaire
        $this->formulaireService->setElement($contactService->getElementFormulaire($type));
        $this->formulaireService->setClassType($contactService->getClassTypeFormulaire($type));
        $this->formulaireService->setTwigFormulaire('symcom4/admin/humains/add_contact.html.twig');
        $this->formulaireService->setTexteConfirmation("Le contact a bien été modifié !");
        $this->formulaireService->setActions($this, array(['name' => 'action_addcontactHumain', 'params' => ['humain' => $humain]]));
        $this->addPageMereFormService();
        $this->createFormService();

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('humain');

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('type', $type);
        $this->defineParamTwig('humain', $humain);

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** HUMAINS - AJOUTER un UTILISATEUR ******************************************************/

    /**
     * Ajouter un utilisateur sur un humain
     * 
     * @Route("/admin/humain/utilisateur/new/{idhumain}/{idpagemere}", name="admin_humain_utilisateur_new")
     *
     * @param integer $idhumain
     * @param integer $idpagemere
     * @return Response
     */
    public function addutilisateurHumain(int $idhumain, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idhumain', 'idpagemere'));

        //Récupérer l'objet Structure et l'objet Fonction
        $humain = $this->outilsService->findById(Humain::class, $idhumain);

        //Gérer le formulaire
        $this->formulaireService->setElement(new Utilisateur());
        $this->formulaireService->setClassType(HumainUtilisateurType::class);
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_utilisateur.html.twig');
        $this->formulaireService->setTexteConfirmation("### a bien été créé !");
        $this->formulaireService->setTexteConfirmationEval(["###" => '$this->element->getPseudo();']);
        $this->formulaireService->setActions($this, array(['name' => 'action_addutilisateurHumain', 'params' => ['humain' => $humain]]));
        $this->addPageMereFormService();
        $this->createFormService();

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('humain');

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** UTILISATEURS - VOIR *******************************************************************/

    /**
     * Voir les utilisateurs
     * 
     * @Route("/admin/utilisateurs", name="admin_utilisateurs")
     *
     * @return Response
     */
    public function utilisateurs():Response
    {
        //Récupérer tous les utilisateurs
        $utilisateurs = $this->outilsService->findAll(Utilisateur::class);

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('humain');

        //Définir le twig à afficher
        $this->defineTwig('symcom4/admin/humains/utilisateurs.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('utilisateurs', $utilisateurs);

        //Afficher la page
        return $this->Afficher();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** ACTIONS *******************************************************************************/

    /**
     * Action - Ajouter un contact à un humain
     *
     * @param Object $contactChild
     * @param Array $params
     * @return Object
     */
    public function action_addcontactHumain(Object $contactChild, Array $params):Object
    {
        $contact = $contactChild->getContact();
        $contact->addHumain($params['humain']);
        return $contactChild;
    }

    /**
     * Action - Ajouter un utilisateur à un humain
     *
     * @param Object $utilisateur
     * @param Array $params
     * @return Object
     */
    public function action_addutilisateurHumain(Object $utilisateur, Array $params):Object
    {
        $utilisateur->setHumain($params['humain']);
        return $utilisateur;
    }















    
    
    
    
    

    

    
    

    

    

    








    // /**
    //  * @Route("/admin/utilisateur/new", name="admin_utilisateur_new")
    //  */
    // public function newUtilisateur(Request $request, EntityManagerInterface $manager):Response
    // {
    //     //On génére la page en cours
    //     $this->pageService->setRoute($request->get('_route'));

    //     //Préparation et traitement du formulaire
    //     $variables['request'] = $request;
    //     $variables['manager'] = $manager;
    //     $variables['element'] = new Utilisateur();
    //     $variables['classType'] = UtilisateurType::class;
    //     $variables['pagedebase'] = 'symcom4/admin/general/form_utilisateur.html.twig';
    //     $variables['pagederesultat'] = 'admin_utilisateurs';
    //     $variables['texteConfirmation'] = "### a bien été créé !"; 
    //     $options['texteConfirmationEval'] = ["###" => '$element->getPseudo();'];
    //     $this->afficherFormulaire($variables, $options);
    //     //Prépare le Twig
    //     $this->initTwig('humain');
    //     //Affiche le formulaire ou la redirection
    //     return $this->Afficher();
    // }

    

    

    
}
