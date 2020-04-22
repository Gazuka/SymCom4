<?php

namespace App\Controller;

use App\Entity\Lien;
use App\Entity\Page;

use App\Entity\Contact;
use App\Entity\Service;
use App\Entity\Fonction;
use App\Entity\Structure;
use App\Entity\Entreprise;
use App\Form\FonctionType;
use App\Form\LienBaseType;
use App\Entity\Association;
use App\Entity\TypeFonction;
use App\Service\PageService;

use App\Form\ServiceBaseType;
use App\Entity\TypeEntreprise;
use App\Form\FonctionBaseType;
use App\Entity\TypeAssociation;
use App\Service\ContactService;
use App\Service\GestionService;
use App\Form\EntrepriseBaseType;
use App\Form\EntrepriseTypeType;
use App\Form\FonctionHumainType;
use App\Form\TypeEntrepriseType;
use App\Form\AssociationBaseType;

use App\Form\AssociationTypeType;
use App\Form\TypeAssociationType;
use App\Controller\AdminController;

use App\Controller\SymCom4Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeFonctionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminStructureController extends AdminController
{
    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** STRUCTURES - AJOUTER, MODIFIER et SUPPRIMER un LIEN ***********************************/

    /**
     * Ajouter un lien à une structure
     *
     * @Route("/admin/structure/addlien/{idstructure}/{idpagemere}", name="admin_structure_addlien")
     * 
     * (test n°3 : affichage)
     * 
     * @param integer $idstructure
     * @param integer $idpagemere
     * @return Response
     */
     public function addlienStructure(int $idstructure, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure', 'idpagemere'));

        //Récupérer l'objet Structure
        $structure = $this->outilsService->findById(Structure::class, $idstructure);

        //Gérer le formulaire
        $this->formulaireService->setElement(new Lien());
        $this->formulaireService->setClassType(LienBaseType::class);
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_lien.html.twig');
        $this->formulaireService->setTexteConfirmation("Le lien a bien été modifié !");
        $this->formulaireService->setActions($this, array(['name' => 'action_addlienStructure', 'params' => ['structure' => $structure]]));
        $this->addPageMereFormService();
        $this->createFormService();

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Modifier le lien d'une structure
     *
     * @Route("/admin/structures/lien/edit/{idstructure}/{idlien}/{idpagemere}", name="admin_structure_editlien")
     * 
     * (test n°4 : affichage)
     * 
     * @param integer $idstructure
     * @param integer $idlien
     * @param integer $idpagemere
     * @return Response
     */
    public function editLienStructure(int $idstructure, int $idlien, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure', 'idlien', 'idpagemere'));

        //Récupérer l'objet Structure et l'objet lien
        $structure = $this->outilsService->findById(Structure::class, $idstructure);
        $lien = $this->outilsService->findById(Lien::class, $idlien);

        //Gérer le formulaire
        $this->formulaireService->setElement($lien);
        $this->formulaireService->setClassType(LienBaseType::class);
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_lien.html.twig');
        $this->formulaireService->setTexteConfirmation("Le lien ### a bien été modifié !");
        $this->formulaireService->setTexteConfirmationEval(["###" => '$this->element->getLabel();']);
        $this->formulaireService->setActions($this, array(['name' => 'action_addlienStructure', 'params' => ['structure' => $structure]]));
        $this->addPageMereFormService();
        $this->createFormService();

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Supprimer le lien d'une structure
     *
     * @Route("/admin/structures/lien/delete/{idstructure}/{idlien}/{idpagemere}", name="admin_structure_deletelien")
     * 
     * (test n°5 : affichage)
     * 
     * @param integer $idstructure
     * @param integer $idlien
     * @param integer $idpagemere
     * @return Response
     */
    public function deleteLienStructure(int $idstructure, int $idlien, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure', 'idlien', 'idpagemere'));

        //Récupérer l'objet Structure et l'objet page
        $structure = $this->outilsService->findById(Structure::class, $idstructure);
        $pageMere = $this->outilsService->findById(Page::class, $idpagemere);

        //Supprimer le lien de la structure
        $structure->setLien(null);

        //Supprimer le lien de la BDD
        $this->outilsService->delete(Lien::class, $idlien);
        
        //Afficher un message de validation
        $this->addFlash('success', 'Le lien a bien été supprimé.');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);

        //Définir la page de redirection
        $this->defineRedirect($pageMere->getNomChemin());
        $this->defineParamRedirect($pageMere->getParams());

        //Afficher la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** STRUCTURES - AJOUTER, MODIFIER et SUPPRIMER un CONTACT ********************************/

    /**
     * Ajouter un contact à la structure
     *
     * @Route("/admin/structure/addcontact/{idstructure}/{idpagemere}/{type}", name="admin_structure_addcontact")
     * 
     * (test n°6 : affichage)
     * 
     * @param integer $idstructure
     * @param integer $idpagemere
     * @param string $type
     * @param ContactService $contactService
     * @return Response
     */
    public function addcontactStructure(int $idstructure, int $idpagemere, string $type, ContactService $contactService):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure', 'idpagemere', 'type'));
        
        //Récupérer l'objet Structure et l'objet page
        $structure = $this->outilsService->findById(Structure::class, $idstructure);
        
        //Gérer le formulaire
        $this->formulaireService->setElement($contactService->getElementFormulaire($type));
        $this->formulaireService->setClassType($contactService->getClassTypeFormulaire($type));
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_contact.html.twig');
        $this->formulaireService->setTexteConfirmation("Le contact a bien été modifié !");
        $this->formulaireService->setActions($this, array(['name' => 'action_addcontactStructure', 'params' => ['structure' => $structure]]));
        $this->addPageMereFormService();
        $this->createFormService();

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);
        $this->defineParamTwig('type', $type);

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Modifier le contact de la structure
     *
     * @Route("/admin/structure/editcontact/{idstructure}/{idcontact}/{idpagemere}", name="admin_structure_editcontact")
     * 
     * (test n°7 : affichage)
     * 
     * @param integer $idstructure
     * @param integer $idcontact
     * @param integer $idpagemere
     * @param ContactService $contactService
     * @return Response
     */
    public function editcontactStructure(int $idstructure, int $idcontact, int $idpagemere, ContactService $contactService):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure', 'idcontact', 'idpagemere'));

        //Récupérer l'objet Structure, l'objet Contact et le type de contact
        $structure = $this->outilsService->findById(Structure::class, $idstructure);
        $contact = $this->outilsService->findById(Contact::class, $idcontact);
        $type = $contact->getType();

        //Gérer le formulaire
        $this->formulaireService->setElement($contactService->getElementFormulaire($type, $contact));
        $this->formulaireService->setClassType($contactService->getClassTypeFormulaire($type));
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_contact.html.twig');
        $this->formulaireService->setTexteConfirmation("Le contact a bien été modifié !");
        $this->addPageMereFormService();
        $this->createFormService();

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);
        $this->defineParamTwig('type', $type);

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Supprimer un contact de la structure
     *
     * @Route("/admin/structure/deletecontact/{idstructure}/{idcontact}/{idpagemere}", name="admin_structure_deletecontact")
     * 
     * @param integer $idstructure
     * @param integer $idcontact
     * @param integer $idpagemere
     * @return Response
     */
    public function deleteContactStructure(int $idstructure, int $idcontact, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure', 'idcontact', 'idpagemere'));

        //Récupérer l'objet Structure, l'objet contact et l'objet page
        $structure = $this->outilsService->findById(Structure::class, $idstructure);
        $contact = $this->outilsService->findById(Contact::class, $idcontact);
        $pageMere = $this->outilsService->findById(Page::class, $idpagemere);

        //Supprimer le contact de la structure
        $structure->removeContact($contact);

        //Supprimer le contact de la BDD
        $this->outilsService->delete(Contact::class, $idcontact);
        
        //Afficher un message de validation
        $this->addFlash('success', 'Le contact a bien été supprimé !');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);

        //Définir la page de redirection
        $this->defineRedirect($pageMere->getNomChemin());
        $this->defineParamRedirect($pageMere->getParams());

        //Afficher la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** STRUCTURES - AJOUTER, MODIFIER et SUPPRIMER une FONCTION ******************************/

    /**
     * Ajouter une fonction à la structure
     *
     * @Route("/admin/structure/addfonction/{idstructure}/{idpagemere}", name="admin_structure_addfonction")
     * 
     * @param integer $idstructure
     * @param integer $idpagemere
     * @return Response
     */
    public function addfonctionStructure(int $idstructure, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure', 'idpagemere'));

        //Récupérer l'objet Structure
        $structure = $this->outilsService->findById(Structure::class, $idstructure);

        //Gérer le formulaire
        $this->formulaireService->setElement(new Fonction());
        $this->formulaireService->setClassType(FonctionBaseType::class);
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_fonction_base.html.twig');
        $this->formulaireService->setTexteConfirmation("La fonction a bien été modifié !");
        $this->formulaireService->setActions($this, array(['name' => 'action_addfonctionStructure', 'params' => ['structure' => $structure]]));
        $this->addPageMereFormService();
        $this->createFormService();

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Modifier une fonction de la structure
     *
     * @Route("/admin/structure/editfonction/{idstructure}/{idfonction}/{idpagemere}", name="admin_structure_editfonction")
     * 
     * @param integer $idstructure
     * @param integer $idfonction
     * @param integer $idpagemere
     * @return Response
     */
    public function editFonctionStructure(int $idstructure, int $idfonction, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure', 'idfonction', 'idpagemere'));

        //Récupérer l'objet Structure et l'objet Fonction
        $structure = $this->outilsService->findById(Structure::class, $idstructure);
        $fonction = $this->outilsService->findById(Fonction::class, $idfonction);

        //Gérer le formulaire
        $this->formulaireService->setElement($fonction);
        $this->formulaireService->setClassType(FonctionBaseType::class);
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_fonction_base.html.twig');
        $this->formulaireService->setTexteConfirmation("La fonction a bien été modifié !");
        $this->addPageMereFormService();
        $this->createFormService();

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);
        $this->defineParamTwig('fonction', $fonction);

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Supprimer un contact de la structure
     *
     * @Route("/admin/structure/deletefonction/{idstructure}/{idfonction}/{idpagemere}", name="admin_structure_deletefonction")
     * 
     * @param integer $idstructure
     * @param integer $idfonction
     * @param integer $idpagemere
     * @return Response
     */
    public function deleteFonctionStructure(int $idstructure, int $idfonction, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure', 'idfonction', 'idpagemere'));

        //Récupérer l'objet Structure, l'objet fonction et l'objet page mère'
        $structure = $this->outilsService->findById(Structure::class, $idstructure);
        $fonction = $this->outilsService->findById(Fonction::class, $idfonction);
        $pageMere = $this->outilsService->findById(Page::class, $idpagemere);

        //Supprimer la fonction de la structure
        $structure->removeFonction($fonction);

        //Supprimer la fonction de la BDD
        $this->outilsService->delete(Fonction::class, $idfonction);
        
        //Afficher un message de validation
        $this->addFlash('success', 'Le fonction a bien été supprimé !');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);

        //Définir la page de redirection
        $this->defineRedirect($pageMere->getNomChemin());
        $this->defineParamRedirect($pageMere->getParams());

        //Afficher la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** STRUCTURES - AJOUTER et SUPPRIMER un HUMAIN d'une FONCTION ****************************/

    /**
     * Ajouter un humain à une fonction de la structure
     *
     * @Route("/admin/structure/fonction/addhumain/{idstructure}/{idfonction}/{idpagemere}", name="admin_structure_fonction_addhumain")
     * 
     * @param integer $idstructure
     * @param integer $idfonction
     * @param integer $idpagemere
     * @return Response
     */
    public function addHumainFonctionStructure(int $idstructure, int $idfonction, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure', 'idfonction', 'idpagemere'));

        //Récupérer l'objet Structure et l'objet Fonction
        $structure = $this->outilsService->findById(Structure::class, $idstructure);
        $fonction = $this->outilsService->findById(Fonction::class, $idfonction);

        //Gérer le formulaire
        $this->formulaireService->setElement($fonction);
        $this->formulaireService->setClassType(FonctionHumainType::class);
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_fonction_humain.html.twig');
        $this->formulaireService->setTexteConfirmation("La fonction a bien été modifié !");
        $this->addPageMereFormService();
        $this->createFormService();

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Supprimer un humain d'une fonction de la structure
     *
     * @Route("/admin/structure/fonction/deletehumain/{idstructure}/{idfonction}/{idpagemere}", name="admin_structure_fonction_deletehumain")
     * 
     * @param integer $idstructure
     * @param integer $idfonction
     * @param integer $idpagemere
     * @return Response
     */
    public function deleteHumainFonctionStructure(int $idstructure, int $idfonction, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure', 'idfonction', 'idpagemere'));

        //Récupérer l'objet Structure, l'objet fonction et l'objet page mère'
        $structure = $this->outilsService->findById(Structure::class, $idstructure);
        $fonction = $this->outilsService->findById(Fonction::class, $idfonction);
        $pageMere = $this->outilsService->findById(Page::class, $idpagemere);

        //Supprimer l'humain de la fonction'
        $structure->removeFonction($fonction);
        $fonction->setHumain(null);

        //Supprimer la fonction de la BDD
        $this->outilsService->delete(Fonction::class, $idfonction);
        
        //Afficher un message de validation
        $this->addFlash('success', 'Le fonction a bien été supprimé !');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);

        //Définir la page de redirection
        $this->defineRedirect($pageMere->getNomChemin());
        $this->defineParamRedirect($pageMere->getParams());

        //Afficher la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** SERVICES - VOIR, AJOUTER, GERER, MODIFIER et SUPPRIMER ********************************/

    /**
     * Afficher l'ensemble des Services
     *
     * @Route("/admin/structures/services", name="admin_structures_services")
     * 
     * @return Response
     */
    public function services(): Response
    {
        //Récupérer tous les services
        $services = $this->outilsService->findAll(Service::class);

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('service');

        //Définir le twig à afficher
        $this->defineTwig('symcom4/admin/structures/services.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('services', $services);

        //Afficher la page
        return $this->Afficher();
    }

    /**
     * Créer un service
     *
     * @Route("/admin/structures/service/new", name="admin_structures_service_new")
     * 
     * @return Response
     */
    public function newService():Response
    {
        //Gérer le formulaire
        $this->formulaireService->setElement(new Service());
        $this->formulaireService->setClassType(ServiceBaseType::class);
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_service_base.html.twig');
        $this->formulaireService->setPageResultat('admin_structures_service');
        $this->formulaireService->setTexteConfirmation("Le service <strong>###</strong> a bien été créé !");
        $this->formulaireService->setTexteConfirmationEval(["###" => '$this->element->getStructure()->getNom();']);
        $this->createFormService();
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('service');
        
        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /** 
     * Gérer d'un service
     *
     * @Route("/admin/structures/service/{idservice}", name="admin_structures_service")
     * 
     * (test-ok - n°1)
     * 
     * @param integer $idservice
     * @return Response
     */
    public function service(int $idservice): Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idservice'));

        //Récupérer l'objet Service
        $service = $this->outilsService->findById(Service::class, $idservice);

        //Préparer le titre et le menu rapide
        $this->initTwig('service');

        //Définir le twig à afficher
        $this->defineTwig('symcom4/admin/structures/service.html.twig'); 

        //Fournir les paramètres requis au Twig      
        $this->defineParamTwig('service', $service);

        //Afficher la page
        return $this->Afficher();
    }

    /**
     * Modifier des informations de base d'un service
     *
     * @Route("/admin/structures/service/edit/{idstructure}/{idpagemere}", name="admin_structures_service_edit")
     * 
     * (test-ok - n°2)
     * 
     * @param integer $idstructure
     * @param integer $idpagemere
     * @return Response
     */
    public function editService(int $idstructure, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure', 'idpagemere'));
        
        //Récupérer l'objet Structure
        $structure = $this->outilsService->findById(Structure::class, $idstructure);
        
        //Gérer le formulaire
        $this->formulaireService->setClassType(ServiceBaseType::class);
        $this->formulaireService->setElement($structure->getService());
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_service_base.html.twig');
        $this->formulaireService->setTexteConfirmation("Le service a bien été modifié !");
        $this->addPageMereFormService();
        $this->createFormService();
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Supprimer un service
     *
     * @Route("/admin/structures/service/delete/{idstructure}", name="admin_structures_service_delete")
     * 
     * @param integer $idstructure
     * @return Response
     */
    public function deleteService(int $idstructure):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure'));

        //Supprimer le service de la BDD
        $this->outilsService->delete(Structure::class, $idstructure);
        
        //Afficher un message de validation
        $this->addFlash('success', 'Le service a bien été supprimé !');
       
        //Définir la page de redirection
        $this->defineRedirect('admin_structures_services');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('service');

        //Afficher la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** ASSOCIATIONS - VOIR, AJOUTER, GERER, MODIFIER et SUPPRIMER ****************************/

    /**
     * Affiche l'ensemble des associations
     *
     * @Route("/admin/structures/associations", name="admin_structures_associations")
     * 
     * @return Response
     */
    public function associations(): Response
    {
        //Récupérer toutes les associations
        $associations = $this->outilsService->findAll(Association::class);

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('association');

        //Définir le twig à afficher
        $this->defineTwig('symcom4/admin/structures/associations.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('associations', $associations);

        //Afficher la page
        return $this->Afficher();
    }

    /**
     * Créer une association
     *
     * @Route("/admin/structures/association/new", name="admin_structures_association_new")
     * 
     * @return Response
     */
    public function newAssociation():Response
    {
        //Gérer le formulaire
        $this->formulaireService->setElement(new Association());
        $this->formulaireService->setClassType(AssociationBaseType::class);
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_association_base.html.twig');
        $this->formulaireService->setPageResultat('admin_structures_association');
        $this->formulaireService->setTexteConfirmation("L'association' <strong>###</strong> a bien été créé !");
        $this->formulaireService->setTexteConfirmationEval(["###" => '$this->element->getStructure()->getNom();']);
        $this->createFormService();
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('association');
        
        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Gérer une association
     * 
     * @Route("/admin/structures/association/{idassociation}", name="admin_structures_association")
     *
     * @param integer $idassociation
     * @return Response
     */
    public function association(int $idassociation): Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idassociation'));

        //Récupérer l'objet Association
        $association = $this->outilsService->findById(Association::class, $idassociation);

        //Préparer le titre et le menu rapide
        $this->initTwig('association');

        //Définir le twig à afficher
        $this->defineTwig('symcom4/admin/structures/association.html.twig'); 

        //Fournir les paramètres requis au Twig      
        $this->defineParamTwig('association', $association);

        //Afficher la page
        return $this->Afficher();
    }

    /**
     * Modifier les informations de base d'une association
     * 
     * @Route("/admin/structures/association/edit/{idstructure}/{idpagemere}", name="admin_structures_association_edit")
     *
     * @param integer $idstructure
     * @param integer $idpagemere
     * @return Response
     */
    public function editAssociation(int $idstructure, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure', 'idpagemere'));
        
        //Récupérer l'objet Structure
        $structure = $this->outilsService->findById(Structure::class, $idstructure);
        
        //Gérer le formulaire
        $this->formulaireService->setClassType(AssociationBaseType::class);
        $this->formulaireService->setElement($structure->getAssociation());
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_association_base.html.twig');
        $this->formulaireService->setTexteConfirmation("L'association a bien été modifiée !");
        $this->addPageMereFormService();
        $this->createFormService();
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Supprimer une association
     * 
     * @Route("/admin/structures/association/delete/{idstructure}", name="admin_structures_association_delete")
     *
     * @param integer $idstructure
     * @return Response
     */
    public function deleteAssociation(int $idstructure):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure'));

        //Supprimer le service de la BDD
        $this->outilsService->delete(Structure::class, $idstructure);
        
        //Afficher un message de validation
        $this->addFlash('success', "L'association' a bien été supprimé !");
       
        //Définir la page de redirection
        $this->defineRedirect('admin_structures_associations');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('association');

        //Afficher la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** TYPES ASSOCIATIONS - VOIR, AJOUTER, GERER, MODIFIER et SUPPRIMER **********************/

    /**
     * Afficher l'ensemble des types d'association
     *
     * @Route("/admin/structures/associations/types", name="admin_structures_associations_types")
     * 
     * @return Response
     */
    public function typeassociations(): Response
    {
        //Récupérer tous les types d'association
        $typeAssociations = $this->outilsService->findAll(TypeAssociation::class);

        //Obtenir le titre et le menu rapide
        $this->initTwig('association');

        //Définir le twig à afficher
        $this->defineTwig('symcom4/admin/structures/typeassociations.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('typeAssociations', $typeAssociations);

        //Afficher la page
        return $this->Afficher();
    }

    /**
     * Créer un type d'association
     * 
     * @Route("/admin/structures/associations/type/new", name="admin_structures_associations_type_new")
     *
     * @return Response
     */
    public function newTypeAssociation():Response
    {
        //Gérer le formulaire
        $this->formulaireService->setElement(new TypeAssociation());
        $this->formulaireService->setClassType(TypeAssociationType::class);
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_typeassociation.html.twig');
        $this->formulaireService->setPageResultat('admin_structures_associations_types');
        $this->formulaireService->setTexteConfirmation("Le type d'association ### a bien été créé !");
        $this->formulaireService->setTexteConfirmationEval(["###" => '$this->element->getNom();']);
        $this->createFormService();
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('association');
        
        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Modifier un type d'association
     * 
     * @Route("/admin/structures/associations/type/edit/{idtypeassociation}", name="admin_structures_associations_type_edit")
     *
     * @param integer $idtypeassociation
     * @return Response
     */
    public function editTypeAssociation(int $idtypeassociation):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idtypeassociation'));
        
        //Récupérer l'objet Structure
        $typeAssociation = $this->outilsService->findById(TypeAssociation::class, $idtypeassociation);
        
        //Gérer le formulaire
        $this->formulaireService->setElement($typeAssociation);
        $this->formulaireService->setClassType(TypeAssociationType::class);
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_typeassociation.html.twig');
        $this->formulaireService->setTexteConfirmation("Le type d'association ### a bien été modifié !");
        $this->formulaireService->setTexteConfirmationEval(["###" => '$this->element->getNom();']);
        $this->formulaireService->setPageResultat('admin_structures_associations_types');
        $this->createFormService();
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('association');

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Supprimer un type d'association
     * 
     * @Route("/admin/structures/associations/type/delete/{idtypeassociation}", name="admin_structures_associations_type_delete")
     *
     * @param integer $idtypeassociation
     * @return Response
     */
    public function deleteTypeAssociation(int $idtypeassociation):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idtypeassociation'));

        //Supprimer le type d'association de la BDD
        $this->outilsService->delete(TypeAssociation::class, $idtypeassociation);
        
        //Afficher un message de validation
        $this->addFlash('success', "Le type d'association a bien été supprimé !");
       
        //Définir la page de redirection
        $this->defineRedirect('admin_structures_typeassociations');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('association');

        //Afficher la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** ASSOCIATIONS - MODIFIER LE TYPE ASSOCIATION *******************************************/

    /**
     * Modifier le type de l'association
     * 
     * @Route("/admin/structures/association/edittype/{idstructure}/{idpagemere}", name="admin_structures_association_edittype")
     *
     * @param integer $idstructure
     * @param integer $idpagemere
     * @return Response
     */
    public function editAssociationType(int $idstructure, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure', 'idpagemere'));
        
        //Récupérer l'objet Structure
        $structure = $this->outilsService->findById(Structure::class, $idstructure);
        
        //Gérer le formulaire
        $this->formulaireService->setClassType(AssociationTypeType::class);
        $this->formulaireService->setElement($structure->getAssociation());
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_association_type.html.twig');
        $this->formulaireService->setTexteConfirmation("L'association a bien été modifié !");
        $this->addPageMereFormService();
        $this->createFormService();
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** ENTREPRISES - VOIR, AJOUTER, GERER, MODIFIER et SUPPRIMER *****************************/

    /**
     * Afficher l'ensemble des entreprises
     * 
     * @Route("/admin/structures/entreprises", name="admin_structures_entreprises")
     *
     * @return Response
     */
    public function entreprises(): Response
    {
        //Récupérer tous les services
        $entreprises = $this->outilsService->findAll(Entreprise::class);

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('entreprise');

        //Définir le twig à afficher
        $this->defineTwig('symcom4/admin/structures/entreprises.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('entreprises', $entreprises);

        //Afficher la page
        return $this->Afficher();
    }

    /**
     * Créer une nouvelle entreprise
     * 
     * @Route("/admin/structures/entreprise/new", name="admin_structures_entreprise_new")
     *
     * @return Response
     */
    public function newEntreprise():Response
    {
        //Gérer le formulaire
        $this->formulaireService->setElement(new Entreprise());
        $this->formulaireService->setClassType(EntrepriseBaseType::class);
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_entreprise_base.html.twig');
        $this->formulaireService->setPageResultat('admin_structures_entreprise');
        $this->formulaireService->setTexteConfirmation("L'entreprise <strong>###</strong> a bien été créé !");
        $this->formulaireService->setTexteConfirmationEval(["###" => '$this->element->getStructure()->getNom();']);
        $this->createFormService();
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('entreprise');
        
        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Gérer une entreprise
     * 
     * @Route("/admin/structures/entreprise/{identreprise}", name="admin_structures_entreprise")
     *
     * @param integer $identreprise
     * @return Response
     */
    public function Entreprise(int $identreprise): Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('identreprise'));

        //Récupérer l'objet Service
        $entreprise = $this->outilsService->findById(Entreprise::class, $identreprise);

        //Préparer le titre et le menu rapide
        $this->initTwig('entreprise');

        //Définir le twig à afficher
        $this->defineTwig('symcom4/admin/structures/entreprise.html.twig'); 

        //Fournir les paramètres requis au Twig      
        $this->defineParamTwig('entreprise', $entreprise);

        //Afficher la page
        return $this->Afficher();
    }

    /**
     * Modifier les informations de base d'une entreprise
     * 
     * @Route("/admin/structures/entreprise/edit/{idstructure}/{idpagemere}", name="admin_structures_entreprise_edit")
     *
     * @param integer $idstructure
     * @param integer $idpagemere
     * @return Response
     */
    public function editEntreprise(int $idstructure, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure', 'idpagemere'));
        
        //Récupérer l'objet Structure
        $structure = $this->outilsService->findById(Structure::class, $idstructure);
        
        //Gérer le formulaire
        $this->formulaireService->setClassType(EntrepriseBaseType::class);
        $this->formulaireService->setElement($structure->getEntreprise());
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_entreprise_base.html.twig');
        $this->formulaireService->setTexteConfirmation("L'entreprise a bien été modifié !");
        $this->addPageMereFormService();
        $this->createFormService();
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Supprimer une entreprise
     * 
     * @Route("/admin/structures/entreprise/delete/{idstructure}", name="admin_structures_entreprise_delete")
     *
     * @param integer $idstructure
     * @return Response
     */
    public function deleteEntreprise(int $idstructure):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure'));

        //Supprimer le service de la BDD
        $this->outilsService->delete(Structure::class, $idstructure);
        
        //Afficher un message de validation
        $this->addFlash('success', "L'entreprise a bien été supprimé !");
       
        //Définir la page de redirection
        $this->defineRedirect('admin_structures_entreprises');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('entreprise');

        //Afficher la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** TYPES ENTREPRISES - VOIR, AJOUTER, GERER, MODIFIER et SUPPRIMER ***********************/

    /**
     * Afficher l'ensemble des types d'entreprise
     * 
     * @Route("/admin/structures/entreprises/types", name="admin_structures_entreprises_types")
     *
     * @return Response
     */
    public function typeentreprises(): Response
    {
        //Récupérer tous les types d'association
        $typeEntreprises = $this->outilsService->findAll(TypeEntreprise::class);

        //Obtenir le titre et le menu rapide
        $this->initTwig('entreprise');

        //Définir le twig à afficher
        $this->defineTwig('symcom4/admin/structures/typeentreprises.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('typeEntreprises', $typeEntreprises);

        //Afficher la page
        return $this->Afficher();
    }

    /**
     * Créer un type d'entreprise
     * 
     * @Route("/admin/structures/entreprises/type/new", name="admin_structures_entreprises_type_new")
     *
     * @return Response
     */
    public function newTypeEntreprise():Response
    {
        //Gérer le formulaire
        $this->formulaireService->setElement(new TypeEntreprise());
        $this->formulaireService->setClassType(TypeEntrepriseType::class);
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_typeentreprise.html.twig');
        $this->formulaireService->setPageResultat('admin_structures_entreprises_types');
        $this->formulaireService->setTexteConfirmation("Le type d'entreprise ### a bien été créé !");
        $this->formulaireService->setTexteConfirmationEval(["###" => '$this->element->getNom();']);
        $this->createFormService();
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('entreprise');
        
        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Modifier un type d'entreprise
     * 
     * @Route("/admin/structures/entreprises/type/edit/{idtypeentreprise}", name="admin_structures_entreprises_type_edit")
     *
     * @param integer $idtypeentreprise
     * @return Response
     */
    public function editTypeEntreprise(int $idtypeentreprise):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idtypeentreprise'));
        
        //Récupérer l'objet Structure
        $typeEntreprise = $this->outilsService->findById(TypeEntreprise::class, $idtypeentreprise);
        
        //Gérer le formulaire
        $this->formulaireService->setElement($typeEntreprise);
        $this->formulaireService->setClassType(TypeEntrepriseType::class);
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_typeentreprise.html.twig');
        $this->formulaireService->setTexteConfirmation("Le type d'entreprise ### a bien été modifié !");
        $this->formulaireService->setTexteConfirmationEval(["###" => '$this->element->getNom();']);
        $this->formulaireService->setPageResultat('admin_structures_typeentreprises');
        $this->createFormService();
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('entreprise');

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Supprimer un type d'entreprise
     * 
     * @Route("/admin/structures/entreprises/type/delete/{idtypeentreprise}", name="admin_structures_entreprises_type_delete")
     *
     * @param integer $idtypeentreprise
     * @return Response
     */
    public function deleteTypeEntreprise(int $idtypeentreprise):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idtypeentreprise'));

        //Supprimer le type d'association de la BDD
        $this->outilsService->delete(TypeEntreprise::class, $idtypeentreprise);
        
        //Afficher un message de validation
        $this->addFlash('success', "Le type d'entreprise a bien été supprimé !");
       
        //Définir la page de redirection
        $this->defineRedirect('admin_structures_typeentreprises');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('entreprise');

        //Afficher la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** ENTREPRISES - MODIFIER LE TYPE ENTREPRISE *********************************************/

    /**
     * Modifier le type de l'entreprise
     * 
     * @Route("/admin/structures/entreprise/edittype/{idstructure}/{idpagemere}", name="admin_structures_entreprise_edittype")
     *
     * @param integer $idstructure
     * @param integer $idpagemere
     * @return Response
     */
    public function editEntrepriseType(int $idstructure, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure', 'idpagemere'));
        
        //Récupérer l'objet Structure
        $structure = $this->outilsService->findById(Structure::class, $idstructure);
        
        //Gérer le formulaire
        $this->formulaireService->setClassType(EntrepriseTypeType::class);
        $this->formulaireService->setElement($structure->getEntreprise());
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_entreprise_type.html.twig');
        $this->formulaireService->setTexteConfirmation("L'entreprise a bien été modifié !");
        $this->addPageMereFormService();
        $this->createFormService();
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** FONCTIONS - VOIR **********************************************************************/

    /**
     * Afficher l'ensemble des fonctions
     *
     * @Route("/admin/fonctions", name="admin_fonctions")
     * 
     * @return Response
     */
    public function fonctions(): Response
    {
        //Récupérer toutes les fonctions
        $fonctions = $this->outilsService->findAll(Fonction::class);

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('fonction');

        //Définir le twig à afficher
        $this->defineTwig('symcom4/admin/fonctions/fonctions.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('fonctions', $fonctions);

        //Afficher la page
        return $this->Afficher();
    }  
    
    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** TYPES FONCTIONS - VOIR, AJOUTER, GERER, MODIFIER et SUPPRIMER ***********************/

    /**
     * Afficher l'ensemble des types de fonctions
     * 
     * @Route("/admin/fonctions/types", name="admin_fonctions_types")
     *
     * @return Response
     */
    public function typefonctions(): Response
    {
        //Récupérer tous les types de fonction
        $typeFonctions = $this->outilsService->findAll(TypeFonction::class);

        //Obtenir le titre et le menu rapide
        $this->initTwig('fonction');

        //Définir le twig à afficher
        $this->defineTwig('symcom4/admin/fonctions/typefonctions.html.twig'); 
        
        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('typeFonctions', $typeFonctions);

        //Afficher la page
        return $this->Afficher();
    }

    /**
     * Créer un type de fonction
     * 
     * @Route("/admin/fonctions/type/new", name="admin_fonctions_type_new")
     *
     * @return Response
     */
    public function newTypeFonction():Response
    {
        //Gérer le formulaire
        $this->formulaireService->setElement(new TypeFonction());
        $this->formulaireService->setClassType(TypeFonctionType::class);
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_typefonction.html.twig');
        $this->formulaireService->setPageResultat('admin_fonctions_types');
        $this->formulaireService->setTexteConfirmation("Le type de fonction ### a bien été créé !");
        $this->formulaireService->setTexteConfirmationEval(["###" => '$this->element->getTitre();']);
        $this->createFormService();
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('fonction');
        
        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Modifier un type de fonction
     * 
     * @Route("/admin/fonctions/type/edit/{idtypefonction}", name="admin_fonctions_type_edit")
     *
     * @param integer $idtypefonction
     * @return Response
     */
    public function editTypeFonction(int $idtypefonction):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idtypefonction'));
        
        //Récupérer l'objet TypeFonction
        $typeFonction = $this->outilsService->findById(TypeFonction::class, $idtypefonction);
        
        //Gérer le formulaire
        $this->formulaireService->setElement($typeFonction);
        $this->formulaireService->setClassType(TypeFonctionType::class);
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_typefonction.html.twig');
        $this->formulaireService->setTexteConfirmation("Le type de fonction ### a bien été modifiée !");
        $this->formulaireService->setTexteConfirmationEval(["###" => '$this->element->getTitre();']);
        $this->formulaireService->setPageResultat('admin_fonctions_types');
        $this->createFormService();
        
        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('fonction');

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Supprimer un type de fonction
     *
     * @Route("/admin/fonctions/type/delete/{idtypefonction}", name="admin_fonctions_type_delete")
     * 
     * @param integer $idtypefonction
     * @return Response
     */
    public function deleteTypeFonction(int $idtypefonction):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idtypefonction'));

        //Supprimer le type d'association de la BDD
        $this->outilsService->delete(TypeFonction::class, $idtypefonction);
        
        //Afficher un message de validation
        $this->addFlash('success', "Le type de fonction a bien été supprimé !");
       
        //Définir la page de redirection
        $this->defineRedirect('admin_fonctions_types');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('fonction');

        //Afficher la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** FONCTIONS - AJOUTER, EDITER et SUPPRIMER un CONTACT ***********************************/

    /**
     * Ajouter un contact à une fonction
     * 
     * @Route("/admin/structure/fonction/addcontact/{idfonction}/{idpagemere}/{type}", name="admin_structure_fonction_addcontact")
     *
     * @param integer $idfonction
     * @param integer $idpagemere
     * @param string $type
     * @param ContactService $contactService
     * @return Response
     */
    public function addcontactStructureFonction(int $idfonction, int $idpagemere, string $type, ContactService $contactService):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idfonction', 'idpagemere', 'type'));

        //Récupérer l'objet Fonction et l'objet Structure
        $fonction = $this->outilsService->findById(Fonction::class, $idfonction);
        $structure = $fonction->getStructure();

        //Gérer le formulaire
        $this->formulaireService->setElement($contactService->getElementFormulaire($type));
        $this->formulaireService->setClassType($contactService->getClassTypeFormulaire($type));
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_contact.html.twig');
        $this->formulaireService->setTexteConfirmation("Le contact a bien été modifié !");
        $this->formulaireService->setActions($this, array(['name' => 'action_addcontactStructure', 'fonction' => ['structure' => $fonction]]));
        $this->addPageMereFormService();
        $this->createFormService();

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('type', $type);
        $this->defineParamTwig('structure', $structure);
        $this->defineParamTwig('fonction', $fonction);

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Modifier le contact d'une fonction
     *
     * @Route("/admin/structure/fonction/editcontact/{idfonction}/{idcontact}/{idpagemere}", name="admin_structure_fonction_editcontact")
     * 
     * @param integer $idfonction
     * @param integer $idcontact
     * @param integer $idpagemere
     * @param ContactService $contactService
     * @return Response
     */
    public function editcontactStructureFonction(int $idfonction, int $idcontact, int $idpagemere, ContactService $contactService):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idfonction', 'idcontact', 'idpagemere'));

        //Récupérer l'objet Fonction, l'objet Structure et l'objet Contact
        $fonction = $this->outilsService->findById(Fonction::class, $idfonction);
        $structure = $fonction->getStructure();
        $contact = $this->outilsService->findById(Contact::class, $idcontact);
        $type = $contact->getType();

        //Gérer le formulaire
        $this->formulaireService->setElement($contactService->getElementFormulaire($type, $contact));
        $this->formulaireService->setClassType($contactService->getClassTypeFormulaire($type));
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_contact.html.twig');
        $this->formulaireService->setTexteConfirmation("Le contact a bien été modifié !");
        $this->addPageMereFormService();
        $this->createFormService();

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($type);

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);
        $this->defineParamTwig('fonction', $fonction);
        $this->defineParamTwig('type', $type);

        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /**
     * Supprimer un contact d'une fonction
     * 
     * @Route("/admin/structure/fonction/deletecontact/{idfonction}/{idcontact}/{idpage}", name="admin_structure_fonction_deletecontact")
     *
     * @param integer $idfonction
     * @param integer $idcontact
     * @param integer $idpagemere
     * @return Response
     */
    public function deleteContactStructureFonction(int $idfonction, int $idcontact, int $idpagemere):Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idfonction', 'idcontact', 'idpagemere'));

        //Récupérer l'objet Fonction, l'objet Structure et l'objet Page mere
        $fonction = $this->outilsService->findById(Fonction::class, $idfonction);
        $structure = $fonction->getStructure();
        $pageMere = $this->outilsService->findById(Page::class, $idpagemere);
        
        

        //Supprimer le contact de la structure
        $structure->removeContact($contact);

        //Supprimer le contact de la BDD
        $this->outilsService->delete(Contact::class, $idcontact);
        
        //Afficher un message de validation
        $this->addFlash('success', 'Le contact a bien été supprimé !');

        //Obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Fournir les paramètres requis au Twig
        $this->defineParamTwig('structure', $structure);
        $this->defineParamTwig('fonction', $fonction);

        //Définir la page de redirection
        $this->defineRedirect($pageMere->getNomChemin());
        $this->defineParamRedirect($pageMere->getParams());

        //Afficher la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /*========================================================================================*/
    /*========================================================================================*/
    /** ACTIONS *******************************************************************************/
    
    /**
     * Action - Ajoute le lien à la structure
     *
     * @param Object $lien
     * @param Array $params
     * @return Object
     */
    public function action_addlienStructure(Object $lien, Array $params):Object
    {
        $lien->setStructure($params['structure']);
        return $lien;
    }

    /**
     * Action - Ajouter un contact à la structure
     *
     * @param Object $contactChild
     * @param Array $params
     * @return Object
     */
    public function action_addcontactStructure(Object $contactChild, Array $params):Object
    {
        $contact = $contactChild->getContact();
        $contact->addStructure($params['structure']);
        return $contactChild;
    }

    /**
     * Action - Ajouter une fonction à la structure
     *
     * @param Object $fonction
     * @param Array $params
     * @return Object
     */
    public function action_addfonctionStructure(Object $fonction, Array $params):Object
    {
        $fonction->setStructure($params['structure']);
        return $fonction;
    }

    /**
     * Action - Ajouter un contact à la fonction
     *
     * @param Object $contactChild
     * @param Array $params
     * @return Object
     */
    public function action_addcontactStructureFonction(Object $contactChild, Array $params):Object
    {
        $contact = $contactChild->getContact();
        $contact->addFonction($params['fonction']);
        return $contactChild;
    }
























    /*========================================================================================*/
    /** Les fonctions - Ajoute, édite et supprime un contact *********************************/

    




    




















    

    

    

    

    

    


    

    

    

    

    
    


    

    

    
    



    


    


    
    

    /*========================================================================================*/
    /** A garder pour la gestion des images sera peut etre utile !!! **************************************************************/
/*
    protected function action_newAssociation($association, $params, $request)
    {
        $dossier = $params['dossierAssociations'];
        //Si le média existe on le récupère
        if($association->getStructure()->getImage() != null)
        {
            //On recupere le media
            $media = $association->getStructure()->getImage()->getMedia();
            //On change son dossier
            $media->deplacer($dossier, $request);
        }
        return $association;
    }
*/
    
    
}
