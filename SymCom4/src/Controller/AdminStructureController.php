<?php

namespace App\Controller;

use App\Entity\Lien;
use App\Entity\Service;
use App\Form\NewLienType;
use App\Form\EditLienType;
use App\Entity\Association;
use App\Form\NewServiceType;
use App\Service\PageService;
use App\Form\ServiceBaseType;
use App\Entity\TypeAssociation;
use App\Service\ContactService;
use App\Service\GestionService;
use App\Form\NewAssociationType;
use App\Repository\LienRepository;
use App\Controller\AdminController;
use App\Form\NewTypeAssociationType;
use App\Controller\SymCom4Controller;
use App\Repository\ContactRepository;
use App\Repository\DossierRepository;
use App\Repository\ServiceRepository;
use App\Repository\StructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AssociationRepository;
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
    public function addlienStructure(Request $request, $idstructure, $idpage, StructureRepository $repo,  EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure); 
        $this->pageService->addParam('idpage', $idpage); 

        //On récupère la structure et la page mère
        $structure = $repo->findOneById($idstructure);
        $pageMere = $this->pageService->recupPageMere($idpage);

        //On crée le nouveau lien et on le passe au formulaire
        $lien = new Lien();
        
        //Lorsque le lien aura un Id, le formulaire devra l'associer avec la structure
        $options['actions'] = array(['name' => 'action_addlienStructure', 'params' => ['structure' => $structure]]);

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $lien;
        $variables['classType'] = NewLienType::class;
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
        $variables['classType'] = NewLienType::class;
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
    public function addcontactStructure(Request $request, $idstructure, $idpage, $type, ContactService $contactService, StructureRepository $repo, EntityManagerInterface $manager):Response
    {
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('idstructure', $idstructure);
        $this->pageService->addParam('idpage', $idpage); 

        //On récupère la structure et la page mère
        $structure = $repo->findOneById($idstructure);
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
        $this->pageService->addParam('idpage', $idpage);

        //On récupère la structure et la page mère
        $structure = $repoStructure->findOneById($idstructure);
        $pageMere = $this->pageService->recupPageMere($idpage);

        //On supprime l'association entre le contact et la structure
        //$structure->removeContact();

        //On supprimer le contact
        $this->delete($repoContact, $manager, $idcontact);

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
    /** Les services **************************************************************************/
       
    /** @Route("/admin/structures/services", name="admin_structures_services")
     * Affichage du tableau avec l'ensemble des services de la BDD
     *
     * @param Request $request
     * @param ServiceRepository $repo
     * @return Response
     */
    public function services(Request $request, ServiceRepository $repo): Response
    {
        //On génère la page en cours
        $this->pageService->setRoute($request->get('_route'));

        //On récupére tous les services
        $services = $repo->findAll();

        //On prépare le Twig
        $this->initTwig('service');
        $this->defineTwig('symcom4/admin/structures/services/services.html.twig');        
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
        //On génére la page en cours
        $this->pageService->setRoute($request->get('_route'));

        //On génère la page en cours puis la récupère
        $this->pageService->setRoute($request->get('_route'));

        //On donne au formulaire la page de resultat et sa config
        $variables['pagederesultat'] = 'admin_structures_service';
        $variables['pagederesultatConfig_NewObjectId'] = true;

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Service();
        $variables['classType'] = ServiceBaseType::class;
        $variables['pagedebase'] = 'symcom4/admin/general/form_service_base.html.twig';
        $variables['texteConfirmation'] = "Le service <strong>###</strong> a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getStructure()->getNom();'];
        $this->afficherFormulaire($variables, $options);
        
        //Prépare le Twig
        $this->initTwig('service');
        
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }

    /** @Route("/admin/structures/service/{id}", name="admin_structures_service")
     * Permet d'afficher la page de gestion d'un service
     *
     * @param Request $request
     * @param [type] $id
     * @param ServiceRepository $repo
     * @param ContactService $contactService
     * @return Response
     */
    public function service(Request $request, $id, ServiceRepository $repo, GestionService $gestionService): Response
    {
        //On génère la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('id', $id); 

        $idPage = $this->pageService->getPageId();      

        //On récupére le service
        $service = $repo->findOneById($id);

        //On passe la page et le service à notre GestionService
        $gestionService->setParent($service->getStructure()); 
        $gestionService->setIdPage($idPage);

        //Prépare le Twig
        $this->initTwig('services');
        $this->defineTwig('symcom4/admin/structures/services/service.html.twig');        
        $this->defineParamTwig('service', $service);
        $this->defineParamTwig('gestionService', $gestionService);
        //$this->defineParamTwig('contactService', $contactService);

        //Affiche la page
        return $this->Afficher();
    }

    /** @Route("/admin/structures/service/edit/{idstructure}/{idpage}", name="admin_structures_service_edit")
     */
    public function editService(Request $request, $idstructure, $idpage, StructureRepository $repoStructure, EntityManagerInterface $manager):Response
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
        $variables['classType'] = ServiceBaseType::class;
        $variables['element'] = $structure->getService();
        $variables['pagedebase'] = 'symcom4/admin/general/form_service_base.html.twig';
        $variables['pagederesultat'] = $pageMere->getNomChemin();
        $variables['pagederesultatConfig'] = $pageMere->getParams();
        $variables['texteConfirmation'] = "Le contact a bien été modifié !"; 
        $options = array();
        $this->afficherFormulaire($variables, $options);

        //Permet d'obtenir le titre et le menu rapide en fonction du type
        $this->initTwig($structure->getType());

        //Prépare le Twig
        $this->defineParamTwig('structure', $structure);

        //Affiche la redirection
        return $this->Afficher();
    }

    /** @Route("/admin/structures/service/delete/{id}", name="admin_structures_service_delete")
     * Page qui supprime un service
     *
     * @param Request $request
     * @param [type] $id
     * @param ServiceRepository $repo
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function deleteService(Request $request, $id, ServiceRepository $repo, EntityManagerInterface $manager):Response
    {
        //On récupère l'id de la page en cours
        $this->pageService->setRoute($request->get('_route'));
        $this->pageService->addParam('id', $id); 
        $idPage = $this->pageService->getPageId();

        //On supprimer le Service
        $this->delete($repo, $manager, $id);

        //Défini la page de redirection
        $this->defineRedirect('admin_structures_services');

        //Prépare le Twig
        $this->initTwig('service');

        //Affiche la redirection
        return $this->Afficher();
    }

    


    












    


    
    /******************************************************************************************/
    /****************************************Les associations *********************************/
    /**
     * @Route("/admin/structures/associations", name="admin_structures_associations")
     */
    public function associations(AssociationRepository $repo): Response
    {
        //Récupére tous les services
        $associations = $repo->findAll();
        //Prépare le Twig
        $this->initTwig('associations');
        $this->defineTwig('symcom4/admin/structures/associations/associations.html.twig');
        $this->defineParamTwig('associations', $associations);
        //Affiche la page
        return $this->Afficher();
    }
    /**
     * @Route("/admin/structures/association/new", name="admin_structures_association_new")
     */
    public function newAssociation(Request $request, EntityManagerInterface $manager, DossierRepository $repoDossier):Response
    {
        $dossierAssociations = $repoDossier->findOneBy(['titre' => 'associations']);

        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Association();
        $variables['classType'] = NewAssociationType::class;
        $variables['pagedebase'] = 'symcom4/admin/structures/associations/new_association.html.twig';
        $variables['pagederesultat'] = 'admin_structures_associations';
        $variables['texteConfirmation'] = "L'association ### a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getStructure()->getNom();'];
        $options['actions'] = array(['name' => 'action_newStructure', 'params' => []], ['name' => 'action_newAssociation', 'params' => ['dossierAssociations' => $dossierAssociations]]);
        

        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('associations');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/structures/association/edit/{id}", name="admin_structures_association_edit")
     */
    public function editAssociation(AssociationRepository $repo, Request $request, EntityManagerInterface $manager, $id):Response
    {
        //On recupére le Service
        $association = $repo->findOneById($id);
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $association;
        $variables['classType'] = NewAssociationType::class;
        $variables['pagedebase'] = 'symcom4/admin/structures/associations/new_association.html.twig';
        $variables['pagederesultat'] = 'admin_structures_associations';
        $variables['texteConfirmation'] = "L'association ### a bien été modifié !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getStructure()->getNom();'];
        $options['actions'] = array(['name' => 'action_newStructure', 'params' => []]);
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('associations');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/structures/association/delete/{id}", name="admin_structures_association_delete")
     */
    public function deleteAssociation(AssociationRepository $repo, EntityManagerInterface $manager, $id):Response
    {
        //On supprimer le Service
        $this->delete($repo, $manager, $id);
        //Défini la page de redirection
        $this->defineRedirect('admin_structures_associations');
        //Prépare le Twig
        $this->initTwig('associations');
        //Affiche la redirection
        return $this->Afficher();
    }
    /******************************************************************************************/
    /****************************************Les types d'associations *************************/
    /**
     * @Route("/admin/structures/associations/types", name="admin_structures_associations_types")
     */
    public function typeAssociations(TypeAssociationRepository $repo): Response
    {
        //Récupére tous les types d'associations
        $typeassociations = $repo->findAll();
        //Prépare le Twig
        $this->initTwig('associations');
        $this->defineTwig('symcom4/admin/structures/associations/types/typeassociations.html.twig');
        $this->defineParamTwig('typeassociations', $typeassociations);
        //Affiche la page
        return $this->Afficher();
    }
    /**
     * @Route("/admin/structures/associations/type/new", name="admin_structures_associations_type_new")
     */
    public function newTypeAssociation(Request $request, EntityManagerInterface $manager):Response
    {
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new TypeAssociation();
        $variables['classType'] = NewTypeAssociationType::class;
        $variables['pagedebase'] = 'symcom4/admin/structures/associations/types/new_typeassociation.html.twig';
        $variables['pagederesultat'] = 'admin_structures_associations';
        $variables['texteConfirmation'] = "Le type d'association ### a bien été créé !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getNom();'];
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('associations');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/structures/associations/type/edit/{id}", name="admin_structures_associations_type_edit")
     */
    public function editTypeAssociation(TypeAssociationRepository $repo, Request $request, EntityManagerInterface $manager, $id):Response
    {
        //On recupére le type d'association
        $typeassociation = $repo->findOneById($id);
        //Préparation et traitement du formulaire
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $typeassociation;
        $variables['classType'] = NewTypeAssociationType::class;
        $variables['pagedebase'] = 'symcom4/admin/structures/associations/types/new_typeassociation.html.twig';
        $variables['pagederesultat'] = 'admin_structures_associations';
        $variables['texteConfirmation'] = "Le type d'association ### a bien été modifié !"; 
        $options['texteConfirmationEval'] = ["###" => '$element->getNom();'];
        $this->afficherFormulaire($variables, $options);
        //Prépare le Twig
        $this->initTwig('associations');
        //Affiche le formulaire ou la redirection
        return $this->Afficher();
    }
    /**
     * @Route("/admin/structures/associations/type/delete/{id}", name="admin_structures_associations_type_delete")
     */
    public function deleteTypeAssociation(TypeAssociationRepository $repo, EntityManagerInterface $manager, $id):Response
    {
        //On supprimer le Service
        $this->delete($repo, $manager, $id);
        //Défini la page de redirection
        $this->defineRedirect('admin_structures_associations');
        //Prépare le Twig
        $this->initTwig('associations');
        //Affiche la redirection
        return $this->Afficher();
    }

    

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

    
    
}
