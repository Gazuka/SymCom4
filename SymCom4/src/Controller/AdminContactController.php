<?php

namespace App\Controller;

use App\Entity\Mail;
use App\Entity\Page;
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
use App\Service\ContactService;
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
        $humains = $this->outilsBox->findEntityBy(Humain::class, [], ['clientMediatheque' => 'ASC', 'nom' => 'ASC', 'prenom' => 'ASC']);

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('humain');

        //Définir le twig à afficher
        $this->outilsBox->defineTwig('symcom4/admin/humains/humains.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('humains', $humains);

        //Afficher la page
        return $this->jobController();
    }

    /**
     * Afficher un humain
     * 
     * @Route("/admin/humain/{idhumain}", name="admin_humain", requirements={"idhumain"="\-?[0-9]+"})
     *
     * @return Response
     */
    public function humain($idhumain):Response
    {
        //Récupérer l'humain
        $humain = $this->outilsBox->findEntityById(Humain::class, $idhumain);

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('humain');

        //Définir le twig à afficher
        $this->outilsBox->defineTwig('symcom4/admin/humains/humain.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('humain', $humain);

        //Afficher la page
        return $this->jobController();
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
        $this->outilsBox->setFormElement(new Humain());
        $this->outilsBox->setFormClassType(NewHumainType::class);
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/humains/new_humain.html.twig');
        $this->outilsBox->setFormPageResultat('admin_humains');
        $this->outilsBox->setFormTexteConfirmation("### a bien été créé !");
        $this->outilsBox->setFormTexteConfirmationEval(["###" => '$this->element->getNom()." ".$this->element->getPrenom();']);
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('humain');
        
        //Afficher le formulaire ou la redirection
        return $this->jobController();
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
        // $this->outilsBox->setPageParams(compact('idhumain'));

        //Récupérer l'objet Humain
        $humain = $this->outilsBox->findEntityById(Humain::class, $idhumain);

        //Gérer le formulaire
        $this->outilsBox->setFormElement($humain);
        $this->outilsBox->setFormClassType(NewHumainType::class);
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/humains/new_humain.html.twig');
        $this->outilsBox->setFormPageResultat('admin_humains');
        $this->outilsBox->setFormTexteConfirmation("### a bien été modifié !");
        $this->outilsBox->setFormTexteConfirmationEval(["###" => '$this->element->getNom()." ".$this->element->getPrenom();']);
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('humain');

        //Afficher le formulaire ou la redirection
        return $this->jobController();
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
        // $this->outilsBox->setPageParams(compact('idhumain'));

        //Supprimer le contact de la BDD
        $this->outilsBox->deleteEntityById(Humain::class, $idhumain);
        
        //Afficher un message de validation
        $this->addFlash('success', 'Cette personne a bien été supprimée !');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('humain');

        //Définir la page de redirection
        $this->outilsBox->defineRedirection('admin_humains');

        //Afficher la redirection
        return $this->jobController();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** HUMAINS - AJOUTER un CONTACT **********************************************************/

    /**
     * Ajouter un contact à un humain
     * 
     * @Route("/admin/humain/addcontact/{idhumain}/{type}/{idpagemere}", name="admin_humain_addcontact")
     *
     * @param integer $idhumain
     * @param string $type
     * @return Response
     */
    public function addcontactHumain(int $idhumain, string $type, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        // // $this->outilsBox->setPageParams(compact('idhumain', 'type'));

        //Récupérer l'objet Humain
        $humain = $this->outilsBox->findEntityById(Humain::class, $idhumain);

        //Gérer le formulaire
        $this->outilsBox->setFormElement($this->contactService->getElementFormulaire($type));
        $this->outilsBox->setFormClassType($this->contactService->getClassTypeFormulaire($type));
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/humains/add_contact.html.twig');
        $this->outilsBox->setFormTexteConfirmation("Le contact a bien été modifié !");
        // $this->outilsBox->setFormPageResultat('admin_humains');
        $this->outilsBox->setFormActions(array(['name' => 'action_addcontactHumain', 'params' => ['humain' => $humain]]));

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('humain');

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('type', $type);
        $this->outilsBox->addParamTwig('humain', $humain);

        //Afficher le formulaire ou la redirection
        return $this->jobController();
    }

    /**
     * Modifier le contact de la structure
     *
     * @Route("/admin/humain/editcontact/{idhumain}/{idcontact}/{idpagemere}", name="admin_humain_editcontact")
     * 
     */
    public function editcontactHumain(int $idhumain, int $idcontact, int $idpagemere, ContactService $contactService):Response
    {
        //Donner les arguments de la page en cours au PageService
        // $this->outilsBox->setPageParams(compact('idhumain', 'idcontact', 'idpagemere'));

        //Récupérer l'objet Humain, l'objet Contact et le type de contact
        $humain = $this->outilsBox->findEntityById(Humain::class, $idhumain);
        $contact = $this->outilsBox->findEntityById(Contact::class, $idcontact);
        $type = $contact->getType();

        //Gérer le formulaire
        $this->outilsBox->setFormElement($contactService->getElementFormulaire($type, $contact));
        $this->outilsBox->setFormClassType($contactService->getClassTypeFormulaire($type));
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_contact.html.twig');
        $this->outilsBox->setFormTexteConfirmation("Le contact a bien été modifié !");
        $this->outilsBox->addParamRedirect('idhumain', $idhumain);
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('humain');

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('humain', $humain);
        $this->outilsBox->addParamTwig('type', $type);

        //Laisser le controller faire son Job avec tout ça...
        return $this->jobController();
    }

    /**
     * Supprimer un contact de l'humain
     *
     * @Route("/admin/humain/deletecontact/{idhumain}/{idcontact}/{idpagemere}", name="admin_humain_deletecontact")
     * 
     */
    public function deleteContactHumain(int $idhumain, int $idcontact, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        // // $this->outilsBox->setPageParams(compact('idhumain', 'idcontact', 'idpagemere'));

        //Récupérer l'objet humain, l'objet contact et l'objet page
        $humain = $this->outilsBox->findEntityById(Humain::class, $idhumain);
        $contact = $this->outilsBox->findEntityById(Contact::class, $idcontact);
        $pageMere = $this->outilsBox->findEntityById(Page::class, $idpagemere);

        //Supprimer le contact de la structure
        $humain->removeContact($contact);
        
        //Supprimer le contact de la BDD
        $this->outilsBox->deleteEntityById(Contact::class, $idcontact);
        
        //Afficher un message de validation
        $this->addFlash('success', 'Le contact a bien été supprimé !');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('humain');

        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('humain', $humain);

        //Définir la page de redirection
        //$this->outilsBox->defineRedirection($this->outilsBox->getPagePageMere());
        // $this->outilsBox->addParamsRedirect($pageMere->getParams());

        //Afficher la redirection
        return $this->jobController();
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
        // $this->outilsBox->setPageParams(compact('idhumain', 'idpagemere'));

        //Récupérer l'objet Structure et l'objet Fonction
        $humain = $this->outilsBox->findEntityById(Humain::class, $idhumain);

        //Gérer le formulaire
        $this->outilsBox->setFormElement(new Utilisateur());
        $this->outilsBox->setFormClassType(HumainUtilisateurType::class);
        $this->outilsBox->setFormTwigFormulaire('symcom4/admin/general/form_utilisateur.html.twig');
        $this->outilsBox->setFormTexteConfirmation("### a bien été créé !");
        $this->outilsBox->setFormTexteConfirmationEval(["###" => '$this->element->getPseudo();']);
        $this->outilsBox->setFormActions(array(['name' => 'action_addutilisateurHumain', 'params' => ['humain' => $humain]]));

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('humain');

        //Afficher le formulaire ou la redirection
        return $this->jobController();
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
        $utilisateurs = $this->outilsBox->findAllEntity(Utilisateur::class);

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('humain');

        //Définir le twig à afficher
        $this->outilsBox->defineTwig('symcom4/admin/humains/utilisateurs.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->outilsBox->addParamTwig('utilisateurs', $utilisateurs);

        //Afficher la page
        return $this->jobController();
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
}