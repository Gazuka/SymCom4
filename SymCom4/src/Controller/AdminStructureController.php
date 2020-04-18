<?php

namespace App\Controller;

use App\Entity\Lien;
use App\Entity\Service;
use App\Entity\Fonction;
use App\Entity\Structure;
use App\Entity\Entreprise;
use App\Form\FonctionType;
use App\Form\LienBaseType;
use App\Entity\Association;
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
use App\Repository\LienRepository;
use App\Controller\AdminController;
use App\Controller\SymCom4Controller;
use App\Repository\ContactRepository;
use App\Repository\DossierRepository;
use App\Repository\ServiceRepository;
use App\Repository\FonctionRepository;
use App\Repository\StructureRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AssociationRepository;
use App\Repository\TypeEntrepriseRepository;
use App\Repository\TypeAssociationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminStructureController extends AdminController
{
    /*========================================================================================*/
    /** Les structures - Ajoute, édite et supprime un lien ************************************/

    /** @Route("/admin/structure/addlien/{idstructure}/{idpage}", name="admin_structure_addlien")
     * Page de modification du lien d'une structure
     *
     * @param Request $request
     * @param [type] $idstructure
     * @param [type] $idpage
     * @param StructureRepository $repo
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function addlienStructure(Request $request, $idstructure, $idpage, StructureRepository $repoStructure,  EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure); 
        $this->pageService->addParam('idpage', $idpage); 

        //On récupère la structure et la page mère
        $structure = $repoStructure->findOneById($idstructure);
        $pageMere = $this->pageService->recupPageMere($idpage);

        //On crée le nouveau lien et on le passe au formulaire
        $lien = new Lien();
        //Le lien est forcément externe au site
        $lien->setExtern(true);
        
        //Lorsque le lien aura un Id, le formulaire devra l'associer avec la structure
        $options['actions'] = array(['name' => 'action_addlienStructure', 'params' => ['structure' => $structure]]);

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $lien;
        $variables['classType'] = LienBaseType::class;
        $variables['pagedebase'] = 'symcom4/admin/general/form_lien.html.twig';
        $variables['pagederesultat'] = $pageMere->getNomChemin();
        $variables['pagederesultatConfig'] = $pageMere->getParams();
        $variables['texteConfirmation'] = "Le lien a bien été modifié !";
        $this->afficherFormulaire($variables, $options);
        
        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('structure', $structure);

        //Affiche la redirection
        return $this->Afficher();
    }

    /** Action - relative à l'ajout d'un lien sur une structure
     *
     * @param [type] $lien
     * @param [type] $params
     * @param [type] $request
     * @return void
     */
    protected function action_addlienStructure($lien, $params, $request)
    {
        $lien->setStructure($params['structure']);
        return $lien;
    }

    /** @Route("/admin/structures/lien/edit/{idstructure}/{idlien}/{idpage}", name="admin_structure_editlien")
     * Permet de modifier le lien d'une structure
     *
     * @param Request $request
     * @param [type] $idstructure
     * @param [type] $idlien
     * @param [type] $idpage
     * @param StructureRepository $repoStructure
     * @param LienRepository $repoLien
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function editLienStructure(Request $request, $idstructure, $idlien, $idpage, StructureRepository $repoStructure, LienRepository $repoLien, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure); 
        $this->pageService->addParam('idlien', $idlien);
        $this->pageService->addParam('idpage', $idpage); 

        //On récupère la structure, le lien et la page mère
        $structure = $repoStructure->findOneById($idstructure);
        $lien = $repoLien->findOneById($idlien);
        $pageMere = $this->pageService->recupPageMere($idpage);

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $lien;
        $variables['classType'] = LienBaseType::class;
        $variables['pagedebase'] = 'symcom4/admin/general/form_lien.html.twig';
        $variables['pagederesultat'] = $pageMere->getNomChemin();
        $variables['pagederesultatConfig'] = $pageMere->getParams();
        $variables['texteConfirmation'] = "Le lien ### a bien été modifié !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getLabel();'];
        $this->afficherFormulaire($variables, $options);

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('structure', $structure);

        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }

    /** @Route("/admin/structures/lien/delete/{idstructure}/{idlien}/{idpage}", name="admin_structure_deletelien")
     * Page qui supprime un lien d'une structure
     *
     * @param Request $request
     * @param [type] $idstructure
     * @param [type] $idlien
     * @param [type] $idpage
     * @param StructureRepository $repoStructure
     * @param LienRepository $repoLien
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function deleteLienStructure(Request $request, $idstructure, $idlien, $idpage, StructureRepository $repoStructure, LienRepository $repoLien, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure); 
        $this->pageService->addParam('idlien', $idlien);
        $this->pageService->addParam('idpage', $idpage); 

        //On récupère la structure et la page mère
        $structure = $repoStructure->findOneById($idstructure);
        $pageMere = $this->pageService->recupPageMere($idpage);

        //On supprime l'association entre le lien et la structure
        $structure->setLien(null);

        //On supprimer le lien de la BDD
        $this->delete($repoLien, $manager, $idlien);

        //On affiche un message de validation
        $this->addFlash('success', 'Le lien a bien été supprimé.');

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('structure', $structure);

        //Défini la page de redirection
        $this->defineRedirect($pageMere->getNomChemin());
        $this->defineParamRedirect($pageMere->getParams());
          
        //Affiche la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /** Les structures - Ajoute, édite et supprime un contact *********************************/

    /** @Route("/admin/structure/addcontact/{idstructure}/{idpage}/{type}", name="admin_structure_addcontact")
     * Permet d'ajouter un contact à la structure (adresse, telephone ou mail)
     *
     * @param Request $request
     * @param [type] $idstructure
     * @param [type] $idpage
     * @param [type] $type
     * @param ContactService $contactService
     * @param StructureRepository $repo
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function addcontactStructure(Request $request, $idstructure, $idpage, $type, ContactService $contactService, StructureRepository $repoStructure, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure);
        $this->pageService->addParam('idpage', $idpage); 
        $this->pageService->addParam('type', $type); 

        //On récupère la structure et la page mère
        $structure = $repoStructure->findOneById($idstructure);
        $pageMere = $this->pageService->recupPageMere($idpage);

        //Selon le type, on crée le type de contact voulu
        $variables = $contactService->addContactConfigForm($type);

        //Lorsque le contact aura un Id, le formulaire devra l'associer avec la structure
        $options['actions'] = array(['name' => 'action_addcontactStructure', 'params' => ['structure' => $structure]]);

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['pagedebase'] = 'symcom4/admin/general/form_contact.html.twig';
        $variables['pagederesultat'] = $pageMere->getNomChemin();
        $variables['pagederesultatConfig'] = $pageMere->getParams();
        $variables['texteConfirmation'] = "Le contact a bien été modifié !";
        $this->afficherFormulaire($variables, $options);

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('type', $type);
        $this->defineParamTwig('structure', $structure);

        //Affiche la redirection
        return $this->Afficher();
    }

    /** Action - relative à l'ajout d'un contact sur une structure
     *
     * @param Object $contactChild
     * @param [type] $params
     * @param [type] $request
     * @return void
     */
    protected function action_addcontactStructure(Object $contactChild, $params, $request)
    {
        $contact = $contactChild->getContact();
        $contact->addStructure($params['structure']);
        return $contactChild;
    }

    /** @Route("/admin/structure/editcontact/{idstructure}/{idcontact}/{idpage}", name="admin_structure_editcontact")
     * Permet l'édition d'un contact d'une structure
     *
     * @param Request $request
     * @param [type] $idstructure
     * @param [type] $idcontact
     * @param [type] $idpage
     * @param StructureRepository $repoStructure
     * @param ContactRepository $repoContact
     * @param ContactService $contactService
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function editcontactStructure(Request $request, $idstructure, $idcontact, $idpage, StructureRepository $repoStructure, ContactRepository $repoContact, ContactService $contactService, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure);
        $this->pageService->addParam('idcontact', $idcontact);
        $this->pageService->addParam('idpage', $idpage);

        //On récupère la structure, le contact et la page mère
        $structure = $repoStructure->findOneById($idstructure);
        $contact = $repoContact->findOneById($idcontact);
        $pageMere = $this->pageService->recupPageMere($idpage);

        //Selon le type, on crée le type de contact voulu
        $variables = $contactService->editContactConfigForm($contact);

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['pagedebase'] = 'symcom4/admin/general/form_contact.html.twig';
        $variables['pagederesultat'] = $pageMere->getNomChemin();
        $variables['pagederesultatConfig'] = $pageMere->getParams();
        $variables['texteConfirmation'] = "Le contact a bien été modifié !"; 
        $options = array();
        $this->afficherFormulaire($variables, $options);

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('structure', $structure);
        $this->defineParamTwig('type', $contact->getType());

        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }

    /** @Route("/admin/structure/deletecontact/{idstructure}/{idcontact}/{idpage}", name="admin_structure_deletecontact")
     * Permet de supprimer un contact associé à une structure
     *
     * @param Request $request
     * @param [type] $idstructure
     * @param [type] $idcontact
     * @param [type] $idpage
     * @param StructureRepository $repoStructure
     * @param ContactRepository $repoContact
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function deleteContactStructure(Request $request, $idstructure, $idcontact, $idpage, StructureRepository $repoStructure, ContactRepository $repoContact, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure);
        $this->pageService->addParam('idcontact', $idcontact);
        $this->pageService->addParam('idpage', $idpage);

        //On récupère la structure et la page mère
        $structure = $repoStructure->findOneById($idstructure);
        $pageMere = $this->pageService->recupPageMere($idpage);

        //On supprime l'association entre le contact et la structure
        //$structure->removeContact();

        //On supprimer le contact
        $this->delete($repoContact, $manager, $idcontact);

        //On affiche un message de validation
        $this->addFlash('success', 'Le contact a bien été supprimé.');

        //Défini la page de redirection
        $this->defineRedirect($pageMere->getNomChemin());
        $this->defineParamRedirect($pageMere->getParams());

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('structure', $structure);

        //Affiche la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /** Les structures - Ajoute, édite et supprime une fonction *******************************/

    /** @Route("/admin/structure/addfonction/{idstructure}/{idpage}", name="admin_structure_addfonction")
     * Permet d'ajouter un contact à la structure (adresse, telephone ou mail)
     *
     * @param Request $request
     * @param [type] $idstructure
     * @param [type] $idpage
     * @param [type] $type
     * @param ContactService $contactService
     * @param StructureRepository $repo
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function addfonctionStructure(Request $request, $idstructure, $idpage, StructureRepository $repoStructure, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure);
        $this->pageService->addParam('idpage', $idpage); 

        //On récupère la structure et la page mère
        $structure = $repoStructure->findOneById($idstructure);
        $pageMere = $this->pageService->recupPageMere($idpage);

        //Lorsque le contact aura un Id, le formulaire devra l'associer avec la structure
        $options['actions'] = array(['name' => 'action_addfonctionStructure', 'params' => ['structure' => $structure]]);

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Fonction();
        $variables['classType'] = FonctionBaseType::class;
        $variables['pagedebase'] = 'symcom4/admin/general/form_fonction_base.html.twig';
        $variables['pagederesultat'] = $pageMere->getNomChemin();
        $variables['pagederesultatConfig'] = $pageMere->getParams();
        $variables['texteConfirmation'] = "La fonction a bien été modifié !";
        $this->afficherFormulaire($variables, $options);

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('structure', $structure);

        //Affiche la redirection
        return $this->Afficher();
    }

    /** Action - relative à l'ajout d'une fonction sur une structure
     *
     * @param Object $contactChild
     * @param [type] $params
     * @param [type] $request
     * @return void
     */
    protected function action_addfonctionStructure(Object $fonction, $params, $request)
    {
        $fonction->setStructure($params['structure']);
        return $fonction;
    }

    /** @Route("/admin/structure/editfonction/{idstructure}/{idfonction}/{idpage}", name="admin_structure_editfonction")
     * Permet l'édition d'une fonction d'une structure
     *
     * @param Request $request
     * @param [type] $idstructure
     * @param [type] $idfonction
     * @param [type] $idpage
     * @param StructureRepository $repoStructure
     * @param FonctionRepository $repoContact
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function editFonctionStructure(Request $request, $idstructure, $idfonction, $idpage, StructureRepository $repoStructure, FonctionRepository $repoFonction, ContactService $contactService, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure);
        $this->pageService->addParam('idfonction', $idfonction);
        $this->pageService->addParam('idpage', $idpage);

        //On récupère la structure, le contact et la page mère
        $structure = $repoStructure->findOneById($idstructure);
        $fonction = $repoFonction->findOneById($idfonction);
        $pageMere = $this->pageService->recupPageMere($idpage);
        
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['classType'] = FonctionBaseType::class;
        $variables['element'] = $fonction;
        $variables['pagedebase'] = 'symcom4/admin/general/form_fonction_base.html.twig';
        $variables['pagederesultat'] = $pageMere->getNomChemin();
        $variables['pagederesultatConfig'] = $pageMere->getParams();
        $variables['texteConfirmation'] = "La fonction a bien été modifié !"; 
        $options = array();
        $this->afficherFormulaire($variables, $options);

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('structure', $structure);
        $this->defineParamTwig('fonction', $fonction);

        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }

    /** @Route("/admin/structure/deletefonction/{idstructure}/{idfonction}/{idpage}", name="admin_structure_deletefonction")
     * Permet de supprimer un contact associé à une structure
     *
     * @param Request $request
     * @param [type] $idstructure
     * @param [type] $idfonction
     * @param [type] $idpage
     * @param StructureRepository $repoStructure
     * @param FonctionRepository $repoFonction
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function deleteFonctionStructure(Request $request, $idstructure, $idfonction, $idpage, StructureRepository $repoStructure, FonctionRepository $repoFonction, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure);
        $this->pageService->addParam('idfonction', $idfonction);
        $this->pageService->addParam('idpage', $idpage);

        //On récupère la structure et la page mère
        $structure = $repoStructure->findOneById($idstructure);
        $pageMere = $this->pageService->recupPageMere($idpage);

        //On supprimer la fonction
        $this->delete($repoFonction, $manager, $idfonction);

        //Défini la page de redirection
        $this->defineRedirect($pageMere->getNomChemin());
        $this->defineParamRedirect($pageMere->getParams());

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('structure', $structure);

        //Affiche la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /** Les structures - Ajoute, édite et supprime un humain de fonction **********************/

    /** @Route("/admin/structure/fonction/addhumain/{idstructure}/{idfonction}/{idpage}", name="admin_structure_fonction_addhumain")
     * Permet d'ajouter un humain sur une fonction de la structure
     */
    public function addHumainFonctionStructure(Request $request, $idstructure, $idfonction, $idpage, StructureRepository $repoStructure, FonctionRepository $repoFonction, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure);
        $this->pageService->addParam('idfonction', $idfonction);
        $this->pageService->addParam('idpage', $idpage); 

        //On récupère la structure et la page mère
        $structure = $repoStructure->findOneById($idstructure);
        $pageMere = $this->pageService->recupPageMere($idpage);
        $fonction = $repoFonction->findOneById($idfonction);

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $fonction;
        $variables['classType'] = FonctionHumainType::class;
        $variables['pagedebase'] = 'symcom4/admin/general/form_fonction_humain.html.twig';
        $variables['pagederesultat'] = $pageMere->getNomChemin();
        $variables['pagederesultatConfig'] = $pageMere->getParams();
        $variables['texteConfirmation'] = "La fonction a bien été modifié !";
        $options = array();
        $this->afficherFormulaire($variables, $options);

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('structure', $structure);

        //Affiche la redirection
        return $this->Afficher();
    }

    /** @Route("/admin/structure/fonction/deletehumain/{idstructure}/{idfonction}/{idpage}", name="admin_structure_fonction_deletehumain")
    * Permet de supprimer un humain associé à une fonction de la structure
    */
    public function deleteHumainFonctionStructure(Request $request, $idstructure, $idfonction, $idpage, StructureRepository $repoStructure, FonctionRepository $repoFonction, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure);
        $this->pageService->addParam('idfonction', $idfonction);
        $this->pageService->addParam('idpage', $idpage); 

        //On récupère la structure et la page mère
        $structure = $repoStructure->findOneById($idstructure);
        $pageMere = $this->pageService->recupPageMere($idpage);
        $fonction = $repoFonction->findOneById($idfonction);

        //On supprimer l'humain de la fonction
        $fonction->setHumain(null);
        $manager->persist($fonction);
        $manager->flush();

        //Défini la page de redirection
        $this->defineRedirect($pageMere->getNomChemin());
        $this->defineParamRedirect($pageMere->getParams());

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('structure', $structure);

        //Affiche la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /** Les services - voir, ajouter, gerer et supprimer **************************************/
       
    /** @Route("/admin/structures/services", name="admin_structures_services")
     * Affichage du tableau avec l'ensemble des services de la BDD
     *
     * @param Request $request
     * @param ServiceRepository $repo
     * @return Response
     */
    public function services(Request $request, ServiceRepository $repoService): Response
    {
        //On génère la page en cours
        $this->pageService->setRoute($request->get('_route'));

        //On récupére tous les services
        $services = $repoService->findAll();

        //On prépare le Twig
        $this->initTwig('service');
        $this->defineTwig('symcom4/admin/structures/services.html.twig');        
        $this->defineParamTwig('services', $services);

        //On affiche la page
        return $this->Afficher();
    }

    /** @Route("/admin/structures/service/new", name="admin_structures_service_new")
     * Affiche le premier formulaire de création d'un service
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function newService(Request $request, EntityManagerInterface $manager):Response
    {
        //Préparer le formulaire
        $this->formulaireService->setClassType(ServiceBaseType::class);
        $this->formulaireService->setElement(new Service());
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_service_base.html.twig');
        $this->formulaireService->setPageResultat('admin_structures_service');
        $this->formulaireService->setTexteConfirmation("Le service <strong>###</strong> a bien été créé !");
        $this->formulaireService->setTexteConfirmationEval(["###" => '$this->element->getStructure()->getNom();']);
        $this->createFormService();
        
        //Préparer le titre et le menu rapide
        $this->initTwig('service');
        
        //Afficher le formulaire ou la redirection
        return $this->Afficher();
    }

    /** 
     * Permet d'afficher la page de gestion d'un service
     *
     * @Route("/admin/structures/service/{idservice}", name="admin_structures_service")
     * 
     * (test-ok - n°1)
     * 
     * @param [int] $idservice
     * @return Response
     */
    public function service($idservice): Response
    {
        //Donner les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idservice'));

        //Récupérer l'objet Service
        $service = $this->outilsService->recupById(Service::class, $idservice);

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
     */
    /**
     * Permet d'éditer les informations de base d'un service
     *
     * @Route("/admin/structures/service/edit/{idstructure}/{idpagemere}", name="admin_structures_service_edit")
     * 
     * (test-ok - n°2)
     * 
     * @param [int] $idstructure
     * @param [int] $idpagemere
     * @return Response
     */
    public function editService($idstructure, $idpagemere):Response
    {
        //On donne les arguments de la page en cours au PageService
        $this->pageService->setParams(compact('idstructure', 'idpagemere'));
        
        //On récupère la structure
        $structure = $this->outilsService->recupById(Structure::class, $idstructure);
        
        //Gestion du formulaire
        $this->formulaireService->setClassType(ServiceBaseType::class);
        $this->formulaireService->setElement($structure->getService());
        $this->formulaireService->setTwigFormulaire('symcom4/admin/general/form_service_base.html.twig');
        $this->formulaireService->setTexteConfirmation("Le service a bien été modifié !");
        $this->addPageMereFormService();
        $this->createFormService();
        
        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('structure', $structure);

        //Affiche la page
        return $this->Afficher();
    }

    /** @Route("/admin/structures/service/delete/{idservice}", name="admin_structures_service_delete")
     * Page qui supprime un service
     *
     * @param Request $request
     * @param [type] $id
     * @param ServiceRepository $repo
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function deleteService(Request $request, $idservice, ServiceRepository $repoService, EntityManagerInterface $manager):Response
    {
        //On géénère la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idservice', $idservice);

        //On supprimer le Service
        $this->delete($repoService, $manager, $idservice);

        //On affiche un message de validation
        $this->addFlash('success', 'Le service a bien été supprimé.');

        //Défini la page de redirection
        $this->defineRedirect('admin_structures_services');

        //Prépare le Twig
        $this->initTwig('service');

        //Affiche la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /** Les associations **********************************************************************/
    
    /** @Route("/admin/structures/associations", name="admin_structures_associations")
     * Affichage du tableau avec l'ensemble des associations de la BDD
     *
     * @param Request $request
     * @param AssociationRepository $repo
     * @return Response
     */
    public function associations(Request $request, AssociationRepository $repoAssociation): Response
    {
        //On génère la page en cours
        $this->pageService->setRoute($request->get('_route'));

        //On récupére toutes les associations
        $associations = $repoAssociation->findAll();

        //On prépare le Twig
        $this->initTwig('association');
        $this->defineTwig('symcom4/admin/structures/associations.html.twig');        
        $this->defineParamTwig('associations', $associations);

        //On affiche la page
        return $this->Afficher();
    }

    /** @Route("/admin/structures/association/new", name="admin_structures_association_new")
     * Affiche le premier formulaire de création d'une association
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function newAssociation(Request $request, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));

        //On donne au formulaire la page de resultat et sa config
        $variables['pagederesultat'] = 'admin_structures_association';
        $variables['pagederesultatConfig_NewObjectId'] = 'idassociation';

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Association();
        $variables['classType'] = AssociationBaseType::class;
        $variables['pagedebase'] = 'symcom4/admin/general/form_association_base.html.twig';
        $variables['texteConfirmation'] = "L'association' <strong>###</strong> a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getStructure()->getNom();'];
        $this->afficherFormulaire($variables, $options);
        
        //Prépare le Twig
        $this->initTwig('association');
        
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }

    /** @Route("/admin/structures/association/{idassociation}", name="admin_structures_association")
     * Permet d'afficher la page de gestion d'une association
     *
     * @param Request $request
     * @param [type] $id
     * @param AssociationRepository $repo
     * @param ContactService $contactService
     * @return Response
     */
    public function association(Request $request, $idassociation, AssociationRepository $repoAssociation, GestionService $gestionService): Response
    {
        //On génère la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idassociation', $idassociation); 

        $idPage = $this->pageService->getPageId();      

        //On récupére l'association'
        $association = $repoAssociation->findOneById($idassociation);

        //On passe la page et le service à notre GestionService
        $gestionService->setParent($association->getStructure()); 
        $gestionService->setIdPage($idPage);

        //Prépare le Twig
        $this->initTwig('association');
        $this->defineTwig('symcom4/admin/structures/association.html.twig');        
        $this->defineParamTwig('association', $association);
        $this->defineParamTwig('gestionService', $gestionService);

        //Affiche la page
        return $this->Afficher();
    }

    /** @Route("/admin/structures/association/edit/{idstructure}/{idpage}", name="admin_structures_association_edit")
     */
    public function editAssociation(Request $request, $idstructure, $idpage, StructureRepository $repoStructure, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure);
        $this->pageService->addParam('idpage', $idpage);
        
        //On récupère la structure et la page mère
        $structure = $repoStructure->findOneById($idstructure);
        $pageMere = $this->pageService->recupPageMere($idpage);
        
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['classType'] = AssociationBaseType::class;
        $variables['element'] = $structure->getAssociation();
        $variables['pagedebase'] = 'symcom4/admin/general/form_association_base.html.twig';
        $variables['pagederesultat'] = $pageMere->getNomChemin();
        $variables['pagederesultatConfig'] = $pageMere->getParams();
        $variables['texteConfirmation'] = "L'association a bien été modifié !"; 
        $options = array();
        $this->afficherFormulaire($variables, $options);

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('structure', $structure);

        //Affiche la redirection
        return $this->Afficher();
    }

    /** @Route("/admin/structures/association/delete/{idstructure}", name="admin_structures_association_delete")
     * Page qui supprime un service
     *
     * @param Request $request
     * @param [type] $id
     * @param StructureRepository $repo
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function deleteAssociation(Request $request, $idstructure, StructureRepository $repoStructure, EntityManagerInterface $manager):Response
    {
        //On génère la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure);

        //On supprimer l'association
        $this->delete($repoStructure, $manager, $idstructure);

        //Défini la page de redirection
        $this->defineRedirect('admin_structures_associations');

        //Prépare le Twig
        $this->initTwig('association');

        //Affiche la redirection
        return $this->Afficher();
    }
    
    /*========================================================================================*/
    /** Les types d'associations **************************************************************/

    /** @Route("/admin/structures/associations/types", name="admin_structures_associations_types")
     * Affichage du tableau avec l'ensemble des types d'associations de la BDD
     *
     * @param Request $request
     * @param TypeAssociationRepository $repoTypeAssociation
     * @return Response
     */
    public function typeassociations(Request $request, TypeAssociationRepository $repoTypeAssociation): Response
    {
        //On génère la page en cours
        $this->pageService->setRoute($request->get('_route'));

        //On récupére toutes les associations
        $typeAssociations = $repoTypeAssociation->findAll();

        //On prépare le Twig
        $this->initTwig('association');
        $this->defineTwig('symcom4/admin/structures/typeassociations.html.twig');        
        $this->defineParamTwig('typeAssociations', $typeAssociations);

        //On affiche la page
        return $this->Afficher();
    }

    /** @Route("/admin/structures/associations/type/new", name="admin_structures_associations_type_new")
     * Affiche le formulaire de création d'un type d'associations
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function newTypeAssociation(Request $request, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));

        //On donne au formulaire la page de resultat et sa config
        $variables['pagederesultat'] = 'admin_structures_associations_types';

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new TypeAssociation();
        $variables['classType'] = TypeAssociationType::class;
        $variables['pagedebase'] = 'symcom4/admin/general/form_typeassociation.html.twig';
        $variables['texteConfirmation'] = "Le type d'association ### a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getNom();'];
        $this->afficherFormulaire($variables, $options);
        
        //Prépare le Twig
        $this->initTwig('association');
        
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }

    /** @Route("/admin/structures/associations/type/edit/{idtypeassociation}", name="admin_structures_associations_type_edit")
     */
    public function editTypeAssociation(Request $request, $idtypeassociation, TypeAssociationRepository $repoTypeAssociation, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idtypeassociation', $idtypeassociation);
        
        //On récupère le type d'association
        $typeAssociation = $repoTypeAssociation->findOneById($idtypeassociation);
        
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['classType'] = TypeAssociationType::class;
        $variables['element'] = $typeAssociation;
        $variables['pagedebase'] = 'symcom4/admin/general/form_typeassociation.html.twig';
        $variables['pagederesultat'] = 'admin_structures_typeassociations';
        $variables['texteConfirmation'] = "Le type d'association ### a bien été modifié !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getNom();'];
        $options = array();
        $this->afficherFormulaire($variables, $options);

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('association');

        //Affiche la redirection
        return $this->Afficher();
    }

    /** @Route("/admin/structures/associations/type/delete/{idtypeassociation}", name="admin_structures_associations_type_delete")
     * Page qui supprime un type d'association
     *
     * @param Request $request
     * @param [type] $idtypeassociation
     * @param typeAssociationRepository $repoTypeAssociation
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function deleteTypeAssociation(Request $request, $idtypeassociation, TypeAssociationRepository $repoTypeAssociation, EntityManagerInterface $manager):Response
    {
        //On récupère l'id de la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idtypeassociation', $idtypeassociation);

        //On supprimer l'association
        $this->delete($repoTypeAssociation, $manager, $idtypeassociation);

        //Défini la page de redirection
        $this->defineRedirect('admin_structures_typeassociations');

        //Prépare le Twig
        $this->initTwig('association');

        //Affiche la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /** Les associations - Modifie le un type ************************************/

    /** @Route("/admin/structures/association/edittype/{idstructure}/{idpage}", name="admin_structures_association_edittype")
     * Permet d'ajouter un type à la structure
     *
     * @param Request $request
     * @param [type] $idstructure
     * @param [type] $idpage
     * @param StructureRepository $repo
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function editAssociationType(Request $request, $idstructure, $idpage, StructureRepository $repoStructure, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure);
        $this->pageService->addParam('idpage', $idpage);
        
        //On récupère la structure et la page mère
        $structure = $repoStructure->findOneById($idstructure);
        $pageMere = $this->pageService->recupPageMere($idpage);
        
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['classType'] = AssociationTypeType::class;
        $variables['element'] = $structure->getAssociation();
        $variables['pagedebase'] = 'symcom4/admin/general/form_association_type.html.twig';
        $variables['pagederesultat'] = $pageMere->getNomChemin();
        $variables['pagederesultatConfig'] = $pageMere->getParams();
        $variables['texteConfirmation'] = "L'association a bien été modifié !"; 
        $options = array();
        $this->afficherFormulaire($variables, $options);

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('structure', $structure);

        //Affiche la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /** Les entreprises ***********************************************************************/
    
    /** @Route("/admin/structures/entreprises", name="admin_structures_entreprises")
     * Affichage du tableau avec l'ensemble des entreprises de la BDD
     *
     * @param Request $request
     * @param EntrepriseRepository $repo
     * @return Response
     */
    public function entreprises(Request $request, EntrepriseRepository $repoEntreprise): Response
    {
        //On génère la page en cours
        $this->pageService->setRoute($request->get('_route'));

        //On récupére toutes les entreprises
        $entreprises = $repoEntreprise->findAll();

        //On prépare le Twig
        $this->initTwig('entreprise');
        $this->defineTwig('symcom4/admin/structures/entreprises.html.twig');        
        $this->defineParamTwig('entreprises', $entreprises);

        //On affiche la page
        return $this->Afficher();
    }

    /** @Route("/admin/structures/entreprise/new", name="admin_structures_entreprise_new")
     * Affiche le premier formulaire de création d'une entreprise
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function newEntreprise(Request $request, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));

        //On donne au formulaire la page de resultat et sa config
        $variables['pagederesultat'] = 'admin_structures_entreprise';
        $variables['pagederesultatConfig_NewObjectId'] = 'identreprise';

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Entreprise();
        $variables['classType'] = EntrepriseBaseType::class;
        $variables['pagedebase'] = 'symcom4/admin/general/form_entreprise_base.html.twig';
        $variables['texteConfirmation'] = "L'entreprise <strong>###</strong> a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getStructure()->getNom();'];
        $this->afficherFormulaire($variables, $options);
        
        //Prépare le Twig
        $this->initTwig('entreprise');
        
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }

    /** @Route("/admin/structures/entreprise/{identreprise}", name="admin_structures_entreprise")
     * Permet d'afficher la page de gestion d'une entreprise
     *
     * @param Request $request
     * @param [type] $id
     * @param EntrepriseRepository $repo
     * @param ContactService $contactService
     * @return Response
     */
    public function Entreprise(Request $request, $identreprise, EntrepriseRepository $repoEntreprise, GestionService $gestionService): Response
    {
        //On génère la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('identreprise', $identreprise); 

        $idPage = $this->pageService->getPageId();      

        //On récupére l'association'
        $entreprise = $repoEntreprise->findOneById($identreprise);

        //On passe la page et l'entreprise à notre GestionService
        $gestionService->setParent($entreprise->getStructure()); 
        $gestionService->setIdPage($idPage);

        //Prépare le Twig
        $this->initTwig('entreprise');
        $this->defineTwig('symcom4/admin/structures/entreprise.html.twig');        
        $this->defineParamTwig('entreprise', $entreprise);
        $this->defineParamTwig('gestionService', $gestionService);

        //Affiche la page
        return $this->Afficher();
    }

    /** @Route("/admin/structures/entreprise/edit/{idstructure}/{idpage}", name="admin_structures_entreprise_edit")
     */
    public function editEntreprise(Request $request, $idstructure, $idpage, StructureRepository $repoStructure, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure);
        $this->pageService->addParam('idpage', $idpage);
        
        //On récupère la structure et la page mère
        $structure = $repoStructure->findOneById($idstructure);
        $pageMere = $this->pageService->recupPageMere($idpage);
        
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['classType'] = EntrepriseBaseType::class;
        $variables['element'] = $structure->getEntreprise();
        $variables['pagedebase'] = 'symcom4/admin/general/form_entreprise_base.html.twig';
        $variables['pagederesultat'] = $pageMere->getNomChemin();
        $variables['pagederesultatConfig'] = $pageMere->getParams();
        $variables['texteConfirmation'] = "L'entreprise a bien été modifié !"; 
        $options = array();
        $this->afficherFormulaire($variables, $options);

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('structure', $structure);

        //Affiche la redirection
        return $this->Afficher();
    }

    /** @Route("/admin/structures/entreprise/delete/{idstructure}", name="admin_structures_entreprise_delete")
     * Page qui supprime un service
     *
     * @param Request $request
     * @param [type] $id
     * @param StructureRepository $repo
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function deleteEntreprise(Request $request, $idstructure, StructureRepository $repoStructure, EntityManagerInterface $manager):Response
    {
        //On génère la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure);

        //On supprimer l'association
        $this->delete($repoStructure, $manager, $idstructure);

        //Défini la page de redirection
        $this->defineRedirect('admin_structures_entreprises');

        //Prépare le Twig
        $this->initTwig('entreprise');

        //Affiche la redirection
        return $this->Afficher();
    }
    
    /*========================================================================================*/
    /** Les types d'entreprises ***************************************************************/

    /** @Route("/admin/structures/entreprises/types", name="admin_structures_entreprises_types")
     * Affichage du tableau avec l'ensemble des types d'entreprises de la BDD
     *
     * @param Request $request
     * @param TypeEntrepriseRepository $repoTypeEntreprise
     * @return Response
     */
    public function typeentreprises(Request $request, TypeEntrepriseRepository $repoTypeEntreprise): Response
    {
        //On génère la page en cours
        $this->pageService->setRoute($request->get('_route'));

        //On récupére toutes les associations
        $typeEntreprises = $repoTypeEntreprise->findAll();

        //On prépare le Twig
        $this->initTwig('entreprise');
        $this->defineTwig('symcom4/admin/structures/typeentreprises.html.twig');        
        $this->defineParamTwig('typeEntreprises', $typeEntreprises);

        //On affiche la page
        return $this->Afficher();
    }

    /** @Route("/admin/structures/entreprises/type/new", name="admin_structures_entreprises_type_new")
     * Affiche le formulaire de création d'un type d'entreprises
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function newTypeEntreprise(Request $request, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));

        //On donne au formulaire la page de resultat et sa config
        $variables['pagederesultat'] = 'admin_structures_entreprises_types';

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new TypeEntreprise();
        $variables['classType'] = TypeEntrepriseType::class;
        $variables['pagedebase'] = 'symcom4/admin/general/form_typeentreprise.html.twig';
        $variables['texteConfirmation'] = "Le type d'entreprise ### a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getNom();'];
        $this->afficherFormulaire($variables, $options);
        
        //Prépare le Twig
        $this->initTwig('entreprise');
        
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }

    /** @Route("/admin/structures/entreprises/type/edit/{idtypeentreprise}", name="admin_structures_entreprises_type_edit")
     */
    public function editTypeEntreprise(Request $request, $idtypeentreprise, TypeEntrepriseRepository $repoTypeEntreprise, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idtypeentreprise', $idtypeentreprise);
        
        //On récupère le type d'entreprise
        $typeEntreprise = $repoTypeEntreprise->findOneById($idtypeentreprise);
        
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['classType'] = TypeEntrepriseType::class;
        $variables['element'] = $typeEntreprise;
        $variables['pagedebase'] = 'symcom4/admin/general/form_typeentreprise.html.twig';
        $variables['pagederesultat'] = 'admin_structures_typeentreprises';
        $variables['texteConfirmation'] = "Le type d'entreprise ### a bien été modifié !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getNom();'];
        $options = array();
        $this->afficherFormulaire($variables, $options);

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig('entreprise');

        //Affiche la redirection
        return $this->Afficher();
    }

    /** @Route("/admin/structures/entreprises/type/delete/{idtypeentreprise}", name="admin_structures_entreprises_type_delete")
     * Page qui supprime un type d'entreprise
     *
     * @param Request $request
     * @param [type] $idtypeentreprise
     * @param typeEntrepriseRepository $repoTypeEntreprise
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function deleteTypeEntreprise(Request $request, $idtypeentreprise, TypeEntrepriseRepository $repoTypeEntreprise, EntityManagerInterface $manager):Response
    {
        //On récupère l'id de la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idtypeentreprise', $idtypeentreprise);

        //On supprimer l'entreprise
        $this->delete($repoTypeEntreprise, $manager, $idtypeentreprise);

        //Défini la page de redirection
        $this->defineRedirect('admin_structures_typeentreprises');

        //Prépare le Twig
        $this->initTwig('entreprise');

        //Affiche la redirection
        return $this->Afficher();
    }

    /*========================================================================================*/
    /** Les entreprises - Modifie le type *****************************************************/

    /** @Route("/admin/structures/entreprise/edittype/{idstructure}/{idpage}", name="admin_structures_entreprise_edittype")
     * Permet d'ajouter un type à la structure
     *
     * @param Request $request
     * @param [type] $idstructure
     * @param [type] $idpage
     * @param StructureRepository $repo
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function editEntrepriseType(Request $request, $idstructure, $idpage, StructureRepository $repoStructure, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure);
        $this->pageService->addParam('idpage', $idpage);
        
        //On récupère la structure et la page mère
        $structure = $repoStructure->findOneById($idstructure);
        $pageMere = $this->pageService->recupPageMere($idpage);
        
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['classType'] = EntrepriseTypeType::class;
        $variables['element'] = $structure->getEntreprise();
        $variables['pagedebase'] = 'symcom4/admin/general/form_entreprise_type.html.twig';
        $variables['pagederesultat'] = $pageMere->getNomChemin();
        $variables['pagederesultatConfig'] = $pageMere->getParams();
        $variables['texteConfirmation'] = "L'entreprise a bien été modifié !"; 
        $options = array();
        $this->afficherFormulaire($variables, $options);

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('structure', $structure);

        //Affiche la redirection
        return $this->Afficher();
    }









    


    


    
    

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
